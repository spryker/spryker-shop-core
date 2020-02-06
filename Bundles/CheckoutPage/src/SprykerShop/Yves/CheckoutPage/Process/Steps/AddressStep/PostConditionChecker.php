<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;

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
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        if ($this->hasItemsWithEmptyShippingAddresses($quoteTransfer)) {
            return false;
        }

        $hasMultipleShippingAddresses = $this->hasMultipleShippingAddresses($quoteTransfer);
        if ($hasMultipleShippingAddresses && $quoteTransfer->getBillingSameAsShipping()) {
            return false;
        }

        if ($this->isQuoteLevelShippingAddressEmpty($quoteTransfer)) {
            return false;
        }

        if ($this->isBillingAddressEmpty($quoteTransfer)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasItemsWithEmptyShippingAddresses(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (
                $itemTransfer->getShipment() === null
                || $this->isAddressEmpty($itemTransfer->getShipment()->getShippingAddress())
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
    protected function hasMultipleShippingAddresses(QuoteTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getItems()->count() <= 1) {
            return false;
        }

        $uniqueAddresses = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $addressUniqueKey = $this->customerService->getUniqueAddressKey($itemTransfer->getShipment()->getShippingAddress());
            $uniqueAddresses[$addressUniqueKey] = 1;

            if (count($uniqueAddresses) > 1) {
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
        return $quoteTransfer->getBillingSameAsShipping() !== true
            && $this->isAddressEmpty($quoteTransfer->getBillingAddress());
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return bool
     */
    public function isAddressEmpty(?AddressTransfer $addressTransfer = null): bool
    {
        if ($addressTransfer === null) {
            return true;
        }

        $firstName = trim($addressTransfer->getFirstName());
        $lastName = trim($addressTransfer->getLastName());

        return empty($firstName) && empty($lastName);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteLevelShippingAddressEmpty(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getItems()->count() === 0
            && $this->isAddressEmpty($quoteTransfer->getShippingAddress());
    }
}
