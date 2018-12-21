<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Controller;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\AvailabilityNotificationWidget\AvailabilityNotificationWidgetFactory getFactory()
 */
class SubscriptionController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function subscribeAction(Request $request)
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            $this->executeSubscribeAction($request);

            return $this->redirectResponseExternal($request->headers->get('referer'));
        }

        $this->executeCustomerSubscribeAction($request);
        return $this->redirectResponseExternal($request->headers->get('referer'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function executeCustomerSubscribeAction(Request $request): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer === null) {
            return;
        }

        $availabilityNotificationSubscriptionTransfer = (new AvailabilityNotificationSubscriptionTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setEmail($customerTransfer->getEmail())
            ->setSku($request->get('sku'));

        $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->subscribe($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @TODO Create functionality for nonauth user CC-1772
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeSubscribeAction(Request $request): array
    {
        $error = false;

        $subscriptionForm = $this
            ->getFactory()
            ->getAvailabilityNotificationSubscriptionForm();

        $parentRequest = $this->getApplication()['request_stack']->getParentRequest();

        if ($parentRequest !== null) {
            $request = $parentRequest;
        }

        $subscriptionForm->handleRequest($request);

        if ($subscriptionForm->isSubmitted() && $subscriptionForm->isValid()) {
//            $customerTransfer = (new CustomerTransfer())
//                ->setEmail($subscriptionForm->get(AvailabilityNotificationSubscriptionForm::FIELD_EMAIL)->getData());
            $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
            $storeTransfer = $this->getFactory()->getStoreFacade()->getCurrentStore();
            $sku = $request->query->get('sku');
            $availabilityNotificationSubscriptionTransfer = (new AvailabilityNotificationSubscriptionTransfer())
                ->setCustomerReference($customerTransfer->getCustomerReference())
                ->setEmail($customerTransfer->getEmail())
                ->setSku()
                ->setStore($storeTransfer);

            $subscriptionResponse = $this->getFactory()
                ->getAvailabilityNotificationClient()
                ->subscribe($availabilityNotificationSubscriptionTransfer);

            if ($subscriptionResponse->getIsSuccess()) {
                $subscriptionForm = $this
                    ->getFactory()
                    ->getAvailabilityNotificationSubscriptionForm();
            }

            if (!$subscriptionResponse->getIsSuccess()) {
                $error = $subscriptionResponse->getErrorMessage();
            }
        }

        return [
            'availabilityNotificationSubscriptionForm' => $subscriptionForm->createView(),
            'error' => $error,
        ];
    }
}
