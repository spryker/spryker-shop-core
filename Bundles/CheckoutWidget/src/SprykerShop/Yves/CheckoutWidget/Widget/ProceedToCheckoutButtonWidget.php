<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CheckoutWidget\CheckoutWidgetFactory getFactory()
 */
class ProceedToCheckoutButtonWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('isVisible', $this->isQuoteApplicableForCheckout($quoteTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProceedToCheckoutButtonWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CheckoutWidget/views/proceed-to-checkout-button/proceed-to-checkout-button.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteApplicableForCheckout(QuoteTransfer $quoteTransfer): bool
    {
        $canProceedCheckoutCheckResponseTransfer = $this->getFactory()
            ->getCheckoutClient()
            ->isQuoteApplicableForCheckout($quoteTransfer);

        return (bool)$canProceedCheckoutCheckResponseTransfer->getIsSuccessful();
    }
}
