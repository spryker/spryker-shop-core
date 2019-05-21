<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Model\Address\AddressDataCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm;

class PostConditionChecker implements PostConditionCheckerInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Model\Address\AddressDataCheckerInterface
     */
    protected $addressDataChecker;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Model\Address\AddressDataCheckerInterface $addressDataChecker
     */
    public function __construct(AddressDataCheckerInterface $addressDataChecker)
    {
        $this->addressDataChecker = $addressDataChecker;
    }

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
            $this->addressDataChecker->isAddressEmpty($quoteTransfer->getBillingAddress());

        if ($billingIsEmpty) {
            return false;
        }

        if ($this->addressDataChecker->isAddressEmpty($quoteTransfer->getShippingAddress()) && $isSplitDelivery === false) {
            return false;
        }

        return true;
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
                || $this->addressDataChecker->isAddressEmpty($itemTransfer->getShipment()->getShippingAddress())
            ) {
                return true;
            }
        }

        return false;
    }
}
