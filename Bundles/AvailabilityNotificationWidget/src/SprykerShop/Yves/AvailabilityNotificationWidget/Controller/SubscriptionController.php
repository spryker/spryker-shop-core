<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Controller;

use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer;
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

        $availabilitySubscriptionExistenceRequestTransfer = (new AvailabilitySubscriptionExistenceRequestTransfer())
            ->setEmail($formData[AvailabilityUnsubscribeForm::FIELD_EMAIL])
            ->setSku($formData[AvailabilityUnsubscribeForm::FIELD_SKU]);

        $subscriptionTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->checkExistence($availabilitySubscriptionExistenceRequestTransfer)
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

        $this->removeAvailabilityNotificationEmailFromSession();
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

        $email = $formData[AvailabilitySubscriptionForm::FIELD_EMAIL];

        if ($customerTransfer === null) {
            $this->addAvailabilityNotificationEmailToSession($email);
        }

        $availabilitySubscriptionTransfer = $this->setAvailabilitySubscriptionTransferFromSubscriptionForm($customerTransfer, $formData);

        $responseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->subscribe($availabilitySubscriptionTransfer);

        if ($responseTransfer->getIsSuccess() === true) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_SUCCESSFULLY_SUBSCRIBED);

            return;
        }

        $this->addErrorMessage($responseTransfer->getErrorMessage());
    }

    /**
     * @param string $email
     *
     * @return void
     */
    protected function addAvailabilityNotificationEmailToSession(string $email): void
    {
        $sessionClient = $this->getFactory()->getSessionClient();
        $sessionClient->set('availabilityNotificationEmail', $email);

        $sessionClient->save();
    }

    /**
     * @return void
     */
    protected function removeAvailabilityNotificationEmailFromSession(): void
    {
        $sessionClient = $this->getFactory()->getSessionClient();
        $sessionClient->set('availabilityNotificationEmail', null);
        $sessionClient->save();
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
