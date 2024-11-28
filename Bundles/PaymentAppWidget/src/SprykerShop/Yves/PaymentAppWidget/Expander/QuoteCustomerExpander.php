<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Expander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuoteCustomerExpander implements QuoteCustomerExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithCustomerData(
        QuoteTransfer $quoteTransfer,
        CustomerTransfer $customerTransfer
    ): QuoteTransfer {
        $customerTransfer = $this->getCustomer($quoteTransfer, $customerTransfer);

        return $quoteTransfer
            ->setBillingAddress($customerTransfer->getBillingAddress()->offsetGet(0))
            ->setCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if ($quoteTransfer->getCustomer() !== null) {
            return $quoteTransfer
                ->getCustomerOrFail()
                ->setBillingAddress($customerTransfer->getBillingAddress())
                ->setShippingAddress($customerTransfer->getShippingAddress());
        }

        return $customerTransfer;
    }
}
