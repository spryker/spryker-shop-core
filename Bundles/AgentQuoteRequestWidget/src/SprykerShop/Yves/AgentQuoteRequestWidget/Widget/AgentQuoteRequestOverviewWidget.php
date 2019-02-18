<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Widget;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestWidget\AgentQuoteRequestWidgetFactory getFactory()
 * @method \SprykerShop\Yves\AgentQuoteRequestWidget\AgentQuoteRequestWidgetConfig getConfig()
 */
class AgentQuoteRequestOverviewWidget extends AbstractWidget
{
    protected const PAGINATION_PAGE = 1;

    public function __construct()
    {
        $this->addParameter('quoteRequestOverviewCollection', $this->getQuoteRequestOverviewCollection());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AgentQuoteRequestOverviewWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@AgentQuoteRequestWidget/views/agent-quote-request-overview/agent-quote-request-overview.twig';
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
            ->setMaxPerPage($this->getConfig()->getPaginationDefaultQuoteRequestsPerPage())
            ->setPage(static::PAGINATION_PAGE);

        $quoteRequestOverviewFilterTransfer = (new QuoteRequestOverviewFilterTransfer())
            ->setQuoteRequestReference($quoteTransfer->getQuoteRequestReference())
            ->setPagination($paginationTransfer);

        return $this->getFactory()
            ->getAgentQuoteRequestClient()
            ->getQuoteRequestOverviewCollection($quoteRequestOverviewFilterTransfer);
    }
}
