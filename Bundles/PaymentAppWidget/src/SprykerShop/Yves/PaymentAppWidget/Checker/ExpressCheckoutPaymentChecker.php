<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Checker;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig;

class ExpressCheckoutPaymentChecker implements ExpressCheckoutPaymentCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isExpressCheckoutPayment(QuoteTransfer $quoteTransfer): bool
    {
        $paymentTransfers = $quoteTransfer->getPayments();
        if (!$paymentTransfers->offsetExists(0)) {
            return false;
        }

        $checkoutConfiguration = $paymentTransfers->offsetGet(0)->getCheckoutConfiguration();
        if (!$checkoutConfiguration || !$checkoutConfiguration->getStrategy()) {
            return false;
        }

        return $checkoutConfiguration->getStrategy() === PaymentAppWidgetConfig::CHECKOUT_CONFIGURATION_STRATEGY_EXPRESS_CHECKOUT;
    }
}
