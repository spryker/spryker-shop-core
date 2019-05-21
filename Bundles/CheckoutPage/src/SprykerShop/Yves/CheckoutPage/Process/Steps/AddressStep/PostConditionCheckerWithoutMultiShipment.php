<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPage\Model\Address\AddressDataCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;

/**
 * @deprecated Use \SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\PostConditionChecker instead.
 */
class PostConditionCheckerWithoutMultiShipment implements PostConditionCheckerInterface
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
        if ($quoteTransfer->getShippingAddress() === null || $quoteTransfer->getBillingAddress() === null) {
            return false;
        }

        $shippingIsEmpty = $this->addressDataChecker->isAddressEmpty($quoteTransfer->getShippingAddress());
        $billingIsEmpty = $quoteTransfer->getBillingSameAsShipping() === false
            && $this->addressDataChecker->isAddressEmpty($quoteTransfer->getBillingAddress());

        if ($shippingIsEmpty || $billingIsEmpty) {
            return false;
        }

        return true;
    }
}
