<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Controller;

use Generated\Shared\Transfer\AvailabilitySubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\AvailabilitySubscriptionForm;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\AvailabilityUnsubscribeForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\AvailabilityNotificationWidget\AvailabilityNotificationWidgetFactory getFactory()
 */
class SubscriptionController extends AbstractController
{
    public const GLOSSARY_KEY_SUCCESSFULLY_SUBSCRIBED = 'availability_notification.subscribed';
    public const GLOSSARY_KEY_SUCCESSFULLY_UNSUBSCRIBED = 'availability_notification.unsubscribed';

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unsubscribeAction(Request $request): RedirectResponse
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
            ->getAvailabilityUnsubscribeForm();
        $unsubscribeForm->handleRequest($request);

        if ($unsubscribeForm->isSubmitted() === false || $unsubscribeForm->isValid() === false) {
            return;
        }

        $formData = $unsubscribeForm->getData();
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        $availabilitySubscriptionRequestTransfer = (new AvailabilitySubscriptionRequestTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setSku($formData[AvailabilityUnsubscribeForm::FIELD_SKU]);

        $subscriptionTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->findAvailabilitySubscription($availabilitySubscriptionRequestTransfer)
            ->getAvailabilitySubscription();

        if ($subscriptionTransfer === null) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_SUCCESSFULLY_UNSUBSCRIBED);

            return;
        }

        $responseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->unsubscribe($subscriptionTransfer);

        if ($responseTransfer->getIsSuccess() === false) {
            $this->addErrorMessage($responseTransfer->getErrorMessage());

            return;
        }

        $availabilitySubscriptionCollection = new AvailabilitySubscriptionCollectionTransfer();

        foreach ($customerTransfer->getAvailabilitySubscriptionCollection()->getAvailabilitySubscriptions() as $availabilitySubscriptionTransfer) {
            if ($availabilitySubscriptionTransfer->getSku() !== $subscriptionTransfer->getSku()) {
                $availabilitySubscriptionCollection->addAvailabilitySubscription($availabilitySubscriptionTransfer);
            }
        }

        $customerTransfer->setAvailabilitySubscriptionCollection($availabilitySubscriptionCollection);

        $this->addSuccessMessage(static::GLOSSARY_KEY_SUCCESSFULLY_UNSUBSCRIBED);
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

        if ($subscriptionForm->isSubmitted() === false || $subscriptionForm->isValid() === false) {
            $errors = $subscriptionForm->getErrors(true);

            foreach ($errors as $error) {
                $this->addErrorMessage($error->getMessage());
            }

            return;
        }

        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $formData = $subscriptionForm->getData();

        $availabilitySubscriptionTransfer = $this->setAvailabilitySubscriptionTransferFromSubscriptionForm($customerTransfer, $formData);

        $responseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->subscribe($availabilitySubscriptionTransfer);

        if ($responseTransfer->getIsSuccess() === true) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_SUCCESSFULLY_SUBSCRIBED);

            if ($customerTransfer !== null) {
                $customerTransfer->getAvailabilitySubscriptionCollection()->addAvailabilitySubscription($responseTransfer->getAvailabilitySubscription());
            }

            return;
        }

        $this->addErrorMessage($responseTransfer->getErrorMessage());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    protected function setAvailabilitySubscriptionTransferFromSubscriptionForm(?CustomerTransfer $customerTransfer, array $formData): AvailabilitySubscriptionTransfer
    {
        $availabilitySubscriptionTransfer = (new AvailabilitySubscriptionTransfer())
            ->setEmail($formData[AvailabilitySubscriptionForm::FIELD_EMAIL])
            ->setSku($formData[AvailabilitySubscriptionForm::FIELD_SKU]);

        if ($customerTransfer === null) {
            return $availabilitySubscriptionTransfer;
        }

        $availabilitySubscriptionTransfer->setCustomerReference($customerTransfer->getCustomerReference());

        return $availabilitySubscriptionTransfer;
    }
}
