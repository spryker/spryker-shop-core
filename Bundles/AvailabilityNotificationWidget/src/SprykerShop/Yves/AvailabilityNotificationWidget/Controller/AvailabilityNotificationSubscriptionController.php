<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\AvailabilityNotificationWidget\AvailabilityNotificationWidgetFactory getFactory()
 */
class AvailabilityNotificationSubscriptionController extends AbstractController
{
    public const GLOSSARY_KEY_SUCCESSFULLY_SUBSCRIBED = 'availability_notification.subscribed';
    public const GLOSSARY_KEY_SUCCESSFULLY_UNSUBSCRIBED = 'availability_notification.unsubscribed';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function subscribeAction(Request $request)
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
            ->createAvailabilitySubscriptionForm();

        $subscriptionForm->handleRequest($request);

        if ($subscriptionForm->isSubmitted() === false || $subscriptionForm->isValid() === false) {
            $errors = $subscriptionForm->getErrors(true);

            foreach ($errors as $error) {
                $this->addErrorMessage($error->getMessage());
            }

            return;
        }

        $availabilitySubscriptionTransfer = $subscriptionForm->getData();
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer !== null) {
            $availabilitySubscriptionTransfer->setCustomerReference($customerTransfer->getCustomerReference());
        }

        $responseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->subscribe($availabilitySubscriptionTransfer);

        if ($responseTransfer->getIsSuccess() === false) {
            $this->addErrorMessage($responseTransfer->getErrorMessage());

            return;
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_SUCCESSFULLY_SUBSCRIBED);

        if ($customerTransfer !== null) {
            $customerTransfer->addAvailabilitySubscription($responseTransfer->getAvailabilitySubscription());
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unsubscribeAction(Request $request)
    {
        $this->executeUnsubscribeAction($request);

        return $this->redirectResponseExternal($request->headers->get('referer'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function executeUnsubscribeAction(Request $request): void
    {
        $unsubscribeForm = $this->getFactory()
            ->createAvailabilityUnsubscribeForm();
        $unsubscribeForm->handleRequest($request);

        if ($unsubscribeForm->isSubmitted() === false || $unsubscribeForm->isValid() === false) {
            return;
        }

        $subscriptionTransfer = $unsubscribeForm->getData();

        $responseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->unsubscribe($subscriptionTransfer);

        if ($responseTransfer->getIsSuccess() === false) {
            $this->addErrorMessage($responseTransfer->getErrorMessage());

            return;
        }

        $this->removeAvailabilitySubscriptionFromCustomer($responseTransfer->getAvailabilitySubscription()->getSku());

        $this->addSuccessMessage(static::GLOSSARY_KEY_SUCCESSFULLY_UNSUBSCRIBED);
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function removeAvailabilitySubscriptionFromCustomer(string $sku): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $availabilitySubscriptions = $customerTransfer->getAvailabilitySubscriptions();

        foreach ($availabilitySubscriptions as $key => $availabilitySubscription) {
            if ($availabilitySubscription->getSku() === $sku) {
                unset($availabilitySubscriptions[$key]);
                break;
            }
        }

        $customerTransfer->setAvailabilitySubscriptions($availabilitySubscriptions);
    }
}
