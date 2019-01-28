<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Widget;

use Generated\Shared\Transfer\FindAvailabilitySubscriptionRequestTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\AvailabilityNotificationWidget\Form\AvailabilitySubscriptionForm;

/**
 * @method \SprykerShop\Yves\AvailabilityNotificationWidget\AvailabilityNotificationWidgetFactory getFactory()
 */
class AvailabilitySubscriptionWidget extends AbstractWidget
{
    protected const SESSION_AVAILABILITY_NOTIFICATION_EMAIL = 'availabilityNotificationEmail';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        $this->addParameter('isSubscribed', $this->getIsSubscribed($productViewTransfer, $customerTransfer));
        $this->addParameter('product', $productViewTransfer);

        $form = $this->getFactory()->getAvailabilitySubscriptionForm();

        $data = [AvailabilitySubscriptionForm::FIELD_SKU => $productViewTransfer->getSku()];

        if ($customerTransfer !== null) {
            $data[AvailabilitySubscriptionForm::FIELD_EMAIL] = $customerTransfer->getEmail();
        }

        $form->setData($data);

        $this->addParameter('subscribeForm', $form->createView());

        $unsubscribeForm = $this->getFactory()->getAvailabilityUnsubscribeForm();
        $unsubscribeData = [AvailabilitySubscriptionForm::FIELD_SKU => $productViewTransfer->getSku()];
        $email = $this->getEmailForUnsubscribeData($customerTransfer);

        if ($email !== null) {
            $unsubscribeData[AvailabilitySubscriptionForm::FIELD_EMAIL] = $email;
        }

        $unsubscribeForm->setData($unsubscribeData);
        $this->addParameter('unsubscribeForm', $unsubscribeForm->createView());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return string|null
     */
    protected function getEmailForUnsubscribeData(?CustomerTransfer $customerTransfer): ?string
    {
        if ($customerTransfer !== null) {
            return $customerTransfer->getEmail();
        }

        return $this->getFactory()
            ->getSessionClient()
            ->get(static::SESSION_AVAILABILITY_NOTIFICATION_EMAIL);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AvailabilitySubscriptionWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@AvailabilityNotificationWidget/views/availability-subscription/availability-subscription.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    protected function getIsSubscribed(ProductViewTransfer $productViewTransfer, ?CustomerTransfer $customerTransfer): bool
    {
        $subscriptionTransfer = $this->setSubscriptionTransfer($customerTransfer, $productViewTransfer);
        if ($subscriptionTransfer->getEmail() === null) {
            return false;
        }

        $availabilitySubscriptionExistenceRequestTransfer = (new FindAvailabilitySubscriptionRequestTransfer())
            ->setEmail($subscriptionTransfer->getEmail())
            ->setSku($productViewTransfer->getSku());

        $availabilitySubscriptionExistenceResponseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->findAvailabilitySubscription($availabilitySubscriptionExistenceRequestTransfer);

        return $availabilitySubscriptionExistenceResponseTransfer->getAvailabilitySubscription() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    protected function setSubscriptionTransfer(?CustomerTransfer $customerTransfer, ProductViewTransfer $productViewTransfer): AvailabilitySubscriptionTransfer
    {
        $subscriptionTransfer = (new AvailabilitySubscriptionTransfer())
            ->setSku($productViewTransfer->getSku());
        if ($customerTransfer === null) {
            $email = $this->getFactory()
                ->getSessionClient()
                ->get(static::SESSION_AVAILABILITY_NOTIFICATION_EMAIL);
            $subscriptionTransfer->setEmail($email);
            return $subscriptionTransfer;
        }

        $subscriptionTransfer->setEmail($customerTransfer->getEmail())
            ->setCustomerReference($customerTransfer->getCustomerReference());

        return $subscriptionTransfer;
    }
}
