<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Widget;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentWidget\QuoteRequestAgentWidgetFactory getFactory()
 * @method \SprykerShop\Yves\QuoteRequestAgentWidget\QuoteRequestAgentWidgetConfig getConfig()
 */
class QuoteRequestAgentOverviewWidget extends AbstractWidget
{
    protected const PAGINATION_PAGE = 1;
    protected const PAGINATION_DEFAULT_QUOTE_REQUESTS_PER_PAGE = 5;

    protected const PARAMETER_CART_FORM = 'cartForm';
    protected const PARAMETER_QUOTE_REQUEST_OVERVIEW_COLLECTION = 'quoteRequestOverviewCollection';

    public function __construct()
    {
        $quoteRequestOverviewCollectionTransfer = $this->getQuoteRequestOverviewCollection();

        $this->addQuoteRequestOverviewCollectionParameter($quoteRequestOverviewCollectionTransfer);

        if ($quoteRequestOverviewCollectionTransfer->getCurrentQuoteRequest()) {
            $this->addCartFormParameter();
        }
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuoteRequestAgentOverviewWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuoteRequestAgentWidget/views/quote-request-agent-overview/quote-request-agent-overview.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer $quoteRequestOverviewCollectionTransfer
     *
     * @return void
     */
    protected function addQuoteRequestOverviewCollectionParameter(
        QuoteRequestOverviewCollectionTransfer $quoteRequestOverviewCollectionTransfer
    ): void {
        $this->addParameter(static::PARAMETER_QUOTE_REQUEST_OVERVIEW_COLLECTION, $quoteRequestOverviewCollectionTransfer);
    }

    /**
     * @return void
     */
    protected function addCartFormParameter(): void
    {
        $this->addParameter(static::PARAMETER_CART_FORM, $this->getFactory()->getQuoteRequestAgentCartForm()->createView());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer
     */
    protected function getQuoteRequestOverviewCollection(): QuoteRequestOverviewCollectionTransfer
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        $paginationTransfer = (new PaginationTransfer())
            ->setMaxPerPage(static::PAGINATION_DEFAULT_QUOTE_REQUESTS_PER_PAGE)
            ->setPage(static::PAGINATION_PAGE);

        $quoteRequestReference = null;

        if ($quoteTransfer->getQuoteRequestVersionReference()) {
            $quoteRequestReference = $quoteTransfer->getQuoteRequestReference();
        }

        $quoteRequestOverviewFilterTransfer = (new QuoteRequestOverviewFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setExcludedStatuses($this->getConfig()->getExcludedOverviewStatuses())
            ->setPagination($paginationTransfer);

        return $this->getFactory()
            ->getQuoteRequestAgentClient()
            ->getQuoteRequestOverviewCollection($quoteRequestOverviewFilterTransfer);
    }
}
