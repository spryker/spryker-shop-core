<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Widget;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestCancelWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     */
    public function __construct(QuoteRequestTransfer $quoteRequestTransfer)
    {
        $this->addParameter('quoteRequest', $quoteRequestTransfer);

        $this->addIsQuoteRequestCancelableParam($quoteRequestTransfer);
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
        return '@QuoteRequestPage/views/quote-request-cancel-link/quote-request-cancel-link.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return void
     */
    protected function addIsQuoteRequestCancelableParam(QuoteRequestTransfer $quoteRequestTransfer): void
    {
        $isQuoteRequestCancelable = $this->getFactory()
            ->getQuoteRequestClient()
            ->isQuoteRequestCancelable($quoteRequestTransfer);

        $this->addParameter('isQuoteRequestCancelable', $isQuoteRequestCancelable);
    }
}
