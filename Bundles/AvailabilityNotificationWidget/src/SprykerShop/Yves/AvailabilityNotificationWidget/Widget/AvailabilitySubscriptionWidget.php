<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityNotificationWidget\Widget;

use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer;
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
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    protected function getIsSubscribed(ProductViewTransfer $productViewTransfer, ?CustomerTransfer $customerTransfer): bool
    {
        if ($customerTransfer === null) {
            return false;
        }

        $availabilitySubscriptionExistenceRequestTransfer = (new AvailabilitySubscriptionExistenceRequestTransfer())
            ->setEmail($customerTransfer->getEmail())
            ->setSku($productViewTransfer->getSku());

        $availabilitySubscriptionExistenceResponseTransfer = $this->getFactory()
            ->getAvailabilityNotificationClient()
            ->checkExistence($availabilitySubscriptionExistenceRequestTransfer);

        return $availabilitySubscriptionExistenceResponseTransfer->getAvailabilitySubscription() !== null;
    }
}
