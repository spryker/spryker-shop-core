<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Widget;

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
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productConcreteTransfer
     */
    public function __construct(ProductViewTransfer $productConcreteTransfer)
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        $this->addParameter('isSubscribed', $this->getIsSubscribed($productConcreteTransfer, $customerTransfer));
        $this->addParameter('product', $productConcreteTransfer);

        $form = $this->getFactory()->getAvailabilitySubscriptionForm();

        $data = [AvailabilitySubscriptionForm::FIELD_SKU => $productConcreteTransfer->getSku()];

        if ($customerTransfer !== null) {
            $data[AvailabilitySubscriptionForm::FIELD_EMAIL] = $customerTransfer->getEmail();
        }

        $form->setData($data);

        $this->addParameter('subscribeForm', $form->createView());

        $unsubscribeForm = $this->getFactory()->getAvailabilityUnsubscribeForm();
        $unsubscribeData = [AvailabilitySubscriptionForm::FIELD_SKU => $productConcreteTransfer->getSku()];
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
        if ($customerTransfer != null) {
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
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    protected function getIsSubscribed(ProductViewTransfer $productConcreteTransfer, ?CustomerTransfer $customerTransfer): bool
    {
        $subscriptionTransfer = $this->setSubscriptionTransfer($customerTransfer, $productConcreteTransfer);
        if ($subscriptionTransfer->getEmail() === null) {
            return false;
        }

        return $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->checkExistence($subscriptionTransfer)
            ->getIsExists();
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
