<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Updater;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer
     *
     * @return void
     */
    public function updateQuoteWithPaymentData(
        QuoteTransfer $quoteTransfer,
        PaymentTransfer $paymentTransfer,
        PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer
    ): void;
}
