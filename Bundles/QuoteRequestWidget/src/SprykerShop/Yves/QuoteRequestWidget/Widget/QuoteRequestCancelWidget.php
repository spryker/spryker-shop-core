<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Widget;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetFactory getFactory()
 */
class QuoteRequestCancelWidget extends AbstractWidget
{
    protected const PARAMETER_QUOTE_REQUEST = 'quoteRequest';
    protected const PARAMETER_IS_QUOTE_REQUEST_CANCELABLE = 'isQuoteRequestCancelable';

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     */
    public function __construct(QuoteRequestTransfer $quoteRequestTransfer)
    {
        $this->addQuoteRequestParameter($quoteRequestTransfer);
        $this->addIsQuoteRequestCancelableParameter($quoteRequestTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuoteRequestCancelWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuoteRequestWidget/views/quote-request-cancel-link/quote-request-cancel-link.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return void
     */
    protected function addQuoteRequestParameter(QuoteRequestTransfer $quoteRequestTransfer): void
    {
        $this->addParameter(static::PARAMETER_QUOTE_REQUEST, $quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return void
     */
    protected function addIsQuoteRequestCancelableParameter(QuoteRequestTransfer $quoteRequestTransfer): void
    {
        $isQuoteRequestCancelable = $this->getFactory()
            ->getQuoteRequestClient()
            ->isQuoteRequestCancelable($quoteRequestTransfer);

        $this->addParameter(static::PARAMETER_IS_QUOTE_REQUEST_CANCELABLE, $isQuoteRequestCancelable);
    }
}
