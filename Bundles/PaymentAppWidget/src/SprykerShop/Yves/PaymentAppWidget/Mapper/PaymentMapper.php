<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Mapper;

use Generated\Shared\Transfer\InitializePreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PaymentMapper implements PaymentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\InitializePreOrderPaymentRequestTransfer $initializePreOrderPaymentRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function mapToPaymentTransfer(
        InitializePreOrderPaymentRequestTransfer $initializePreOrderPaymentRequestTransfer,
        QuoteTransfer $quoteTransfer,
        PaymentTransfer $paymentTransfer
    ): PaymentTransfer {
        return $paymentTransfer
            ->fromArray($initializePreOrderPaymentRequestTransfer->toArray(), true)
            ->setPaymentProviderName($initializePreOrderPaymentRequestTransfer->getPaymentProviderOrFail())
            ->setPaymentMethodName($initializePreOrderPaymentRequestTransfer->getPaymentMethodOrFail())
            ->setAmount($quoteTransfer->getTotalsOrFail()->getGrandTotal());
    }
}
