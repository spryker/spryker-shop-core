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

        $this->addParameter('form', $form->createView());
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
        if ($customerTransfer === null) {
            return false;
        }

        $subscriptionTransfer = (new AvailabilitySubscriptionTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setEmail($customerTransfer->getEmail())
            ->setSku($productConcreteTransfer->getSku());

        $subscriptionResponseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->checkSubscription($subscriptionTransfer);

        return $subscriptionResponseTransfer->getIsSuccess();
    }
}
