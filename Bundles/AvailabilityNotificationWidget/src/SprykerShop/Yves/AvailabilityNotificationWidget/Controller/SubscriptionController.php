<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Controller;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\AvailabilitySubscriptionForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function subscribeAction(Request $request): RedirectResponse
    {
        $this->executeSubscribeAction($request);

        return $this->redirectResponseExternal($request->headers->get('referer'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function executeSubscribeAction(Request $request): void
    {
        $subscriptionForm = $this
            ->getFactory()
            ->getAvailabilitySubscriptionForm();

        $subscriptionForm->handleRequest($request);

        if ($subscriptionForm->isSubmitted() && $subscriptionForm->isValid()) {
            $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

            if ($customerTransfer === null) {
                return;
            }

            $formData = $subscriptionForm->getData();

            $availabilitySubscriptionTransfer = (new AvailabilitySubscriptionTransfer())
                ->setCustomerReference($customerTransfer->getCustomerReference())
                ->setEmail($formData[AvailabilitySubscriptionForm::FIELD_EMAIL])
                ->setSku($formData[AvailabilitySubscriptionForm::FIELD_SKU]);

            $this->getFactory()
                ->getAvailabilityNotificationClient()
                ->subscribe($availabilitySubscriptionTransfer);
        }
    }
}
