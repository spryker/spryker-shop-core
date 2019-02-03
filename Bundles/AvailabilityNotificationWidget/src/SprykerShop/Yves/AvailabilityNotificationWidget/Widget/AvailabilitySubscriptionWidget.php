<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Widget;

use Generated\Shared\Transfer\AvailabilitySubscriptionRequestTransfer;
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

        $isSubscribed = false;

        if ($customerTransfer !== null) {
            $isSubscribed = $this->getIsSubscribed($productViewTransfer, $customerTransfer);
        }

        $this->addParameter('isSubscribed', $isSubscribed);
        $this->addParameter('product', $productViewTransfer);

        if ($isSubscribed === false) {
            $subscribeForm = $this->getFactory()->getAvailabilitySubscriptionForm();

            $subscribeData = [AvailabilitySubscriptionForm::FIELD_SKU => $productViewTransfer->getSku()];

            if ($customerTransfer !== null) {
                $subscribeData[AvailabilitySubscriptionForm::FIELD_EMAIL] = $customerTransfer->getEmail();
            }

            $subscribeForm->setData($subscribeData);
            $this->addParameter('subscribeForm', $subscribeForm->createView());

            return;
        }

        $unsubscribeForm = $this->getFactory()->getAvailabilityUnsubscribeForm();
        $unsubscribeData = [AvailabilitySubscriptionForm::FIELD_SKU => $productViewTransfer->getSku()];
        $unsubscribeForm->setData($unsubscribeData);
        $this->addParameter('unsubscribeForm', $unsubscribeForm->createView());
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function getIsSubscribed(ProductViewTransfer $productViewTransfer, CustomerTransfer $customerTransfer): bool
    {
        $availabilitySubscriptionCollection = $customerTransfer->getAvailabilitySubscriptionCollection();

        foreach ($availabilitySubscriptionCollection->getAvailabilitySubscriptions() as $availabilitySubscriptionTransfer) {
            if ($availabilitySubscriptionTransfer->getSku() === $productViewTransfer->getSku()) {
                return true;
            }
        }

        return false;
    }
}
