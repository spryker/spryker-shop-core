<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerShop\Yves\PaymentAppWidget\Plugin\CheckoutPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepPreConditionPluginInterface;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory getFactory()
 */
class PaymentAppCancelOrderOnSummaryPageAfterRedirectFromHostedPaymentPagePlugin extends AbstractPlugin implements CheckoutStepPreConditionPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preCondition(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->createOrder()->cancelOrder($quoteTransfer);
    }
}
