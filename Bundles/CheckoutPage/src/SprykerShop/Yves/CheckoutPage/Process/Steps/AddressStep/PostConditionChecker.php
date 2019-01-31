<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;

class PostConditionChecker implements PostConditionCheckerInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(AbstractTransfer $quoteTransfer): bool
    {
        if (!$this->isShipmentSet($quoteTransfer) || !$this->isItemShipmentSet($quoteTransfer)) {
            return false;
        }

        // todo: Split Delivery check multiple addresses flag in case billing address is the same as Shipping address
        $shippingIsEmpty = $this->isAddressEmpty($quoteTransfer->getShippingAddress());
        $billingIsEmpty = $quoteTransfer->getBillingSameAsShipping() === false && $this->isAddressEmpty($quoteTransfer->getBillingAddress());

        if ($shippingIsEmpty || $billingIsEmpty) {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentSet(AbstractTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getShippingAddress() === null || $quoteTransfer->getBillingAddress() === null) {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isItemShipmentSet(AbstractTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null || $itemTransfer->getShipment()->getShippingAddress() === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return bool
     */
    protected function isAddressEmpty(?AddressTransfer $addressTransfer = null): bool
    {
        if ($addressTransfer === null) {
            return true;
        }

        $hasName = (!empty($addressTransfer->getFirstName()) && !empty($addressTransfer->getLastName()));
        if (!$addressTransfer->getIdCustomerAddress() && !$hasName) {
            return true;
        }

        return false;
    }
}
