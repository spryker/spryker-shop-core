<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetFactory getFactory()
 */
class QuoteRequestCheckoutAddressWidget extends AbstractWidget
{
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addIsVisibleParameter($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuoteRequestCheckoutAddressWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuoteRequestWidget/views/quote-request-checkout/quote-request-checkout-address.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addIsVisibleParameter(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(
            static::PARAMETER_IS_VISIBLE,
            $this->getFactory()->getQuoteRequestClient()->isQuoteInQuoteRequestProcess($quoteTransfer)
        );
    }
}
