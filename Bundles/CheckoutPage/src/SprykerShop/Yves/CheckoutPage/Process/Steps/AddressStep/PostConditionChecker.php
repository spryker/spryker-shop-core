<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm;

class PostConditionChecker implements PostConditionCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(AbstractTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getBillingAddress() === null) {
            return false;
        }

        if ($this->hasItemsWithEmptyShippingAddresses($quoteTransfer)) {
            return false;
        }

        $isSplitDelivery = $this->isSplitDelivery($quoteTransfer);
        if ($isSplitDelivery && $quoteTransfer->getBillingSameAsShipping()) {
            return false;
        }

        $billingIsEmpty = $quoteTransfer->getBillingSameAsShipping() === false &&
            $this->isAddressEmpty($quoteTransfer->getBillingAddress());

        if ($billingIsEmpty) {
            return false;
        }

        if ($this->isAddressEmpty($quoteTransfer->getShippingAddress()) && $isSplitDelivery === false) {
            return false;
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

        return $addressTransfer->getIdCustomerAddress() === null
            && empty($addressTransfer->getFirstName())
            && empty($addressTransfer->getLastName());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isSplitDelivery(AbstractTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShippingAddress()->getIdCustomerAddress() === CheckoutAddressForm::VALUE_DELIVER_TO_MULTIPLE_ADDRESSES;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasItemsWithEmptyShippingAddresses(AbstractTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null
                || $this->isAddressEmpty($itemTransfer->getShipment()->getShippingAddress())) {
                return true;
            }
        }

        return false;
    }
}
