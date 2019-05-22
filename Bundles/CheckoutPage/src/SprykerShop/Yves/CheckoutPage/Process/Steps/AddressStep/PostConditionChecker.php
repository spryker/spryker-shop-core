<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;
use SprykerShop\Yves\CustomerPage\Form\CheckoutAddressForm;

class PostConditionChecker implements PostConditionCheckerInterface
{
    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface
     */
    protected $customerService;

    /**
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface $customerService
     */
    public function __construct(CheckoutPageToCustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;
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

        if ($this->isBillingAddressEmpty($quoteTransfer)) {
            return false;
        }

        $isQuoteShippingAddressEmpty = $this->customerService->isAddressEmpty($quoteTransfer->getShippingAddress());
        if ($isQuoteShippingAddressEmpty && $isSplitDelivery === false) {
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
                || $this->customerService->isAddressEmpty($itemTransfer->getShipment()->getShippingAddress())
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBillingAddressEmpty(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getBillingSameAsShipping() === false
            && $this->customerService->isAddressEmpty($quoteTransfer->getBillingAddress());
    }
}
