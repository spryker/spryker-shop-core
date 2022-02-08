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
    /**
     * @var string
     */
    public const GLOSSARY_KEY_SUCCESSFULLY_SUBSCRIBED = 'availability_notification.subscribed';

    /**
     * @var string
     */
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
            ->createAvailabilityNotificationSubscriptionForm();

        $subscriptionForm->handleRequest($request);

        if ($subscriptionForm->isSubmitted() === false || $subscriptionForm->isValid() === false) {
            /** @var array<\Symfony\Component\Form\FormError> $errors */
            $errors = $subscriptionForm->getErrors(true);
            foreach ($errors as $error) {
                $this->addErrorMessage($error->getMessage());
            }

            return;
        }

        /** @var \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer */
        $availabilityNotificationSubscriptionTransfer = $subscriptionForm->getData();
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer !== null) {
            $availabilityNotificationSubscriptionTransfer->setCustomerReference($customerTransfer->getCustomerReference());
        }

        $responseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->subscribe($availabilityNotificationSubscriptionTransfer);

        if ($responseTransfer->getIsSuccess() === false) {
            $this->addErrorMessage($responseTransfer->getErrorMessage());

            return;
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_SUCCESSFULLY_SUBSCRIBED);

        if ($customerTransfer !== null) {
            $customerTransfer->addAvailabilityNotificationSubscriptionSku($responseTransfer->getAvailabilityNotificationSubscription()->getSku());
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

        /** @var \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $subscriptionTransfer */
        $subscriptionTransfer = $unsubscribeForm->getData();
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer === null) {
            return;
        }

        $subscriptionTransfer->setCustomerReference($customerTransfer->getCustomerReference());

        $responseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->unsubscribeByCustomerReferenceAndSku($subscriptionTransfer);

        if ($responseTransfer->getIsSuccess() === false) {
            $this->addErrorMessage($responseTransfer->getErrorMessage());

            return;
        }

        $this->removeAvailabilityNotificationSubscriptionFromCustomer($responseTransfer->getAvailabilityNotificationSubscription()->getSku());

        $this->addSuccessMessage(static::GLOSSARY_KEY_SUCCESSFULLY_UNSUBSCRIBED);
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function removeAvailabilityNotificationSubscriptionFromCustomer(string $sku): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer === null) {
            return;
        }

        $availabilityNotificationSubscriptionSkus = $customerTransfer->getAvailabilityNotificationSubscriptionSkus();

        $key = array_search($sku, $availabilityNotificationSubscriptionSkus);

        if ($key !== false) {
            unset($availabilityNotificationSubscriptionSkus[$key]);
        }

        $customerTransfer->setAvailabilityNotificationSubscriptionSkus($availabilityNotificationSubscriptionSkus);
    }
}
