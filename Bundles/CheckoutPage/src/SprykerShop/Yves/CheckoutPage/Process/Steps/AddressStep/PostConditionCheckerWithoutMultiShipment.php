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

/**
 * @deprecated Use \SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\PostConditionChecker instead.
 */
class PostConditionCheckerWithoutMultiShipment implements PostConditionCheckerInterface
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
        if ($quoteTransfer->getShippingAddress() === null || $quoteTransfer->getBillingAddress() === null) {
            return false;
        }

        $shippingIsEmpty = $this->customerService->isAddressEmpty($quoteTransfer->getShippingAddress());
        $billingIsEmpty = $this->isBillingAddressEmpty($quoteTransfer);

        if ($shippingIsEmpty || $billingIsEmpty) {
            return false;
        }

        return true;
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
