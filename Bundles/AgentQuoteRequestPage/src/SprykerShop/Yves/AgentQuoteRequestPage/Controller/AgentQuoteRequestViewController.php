<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Controller;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 */
class AgentQuoteRequestViewController extends AgentQuoteRequestAbstractController
{
    protected const PARAM_PAGE = 'page';
    protected const DEFAULT_PAGE = 1;
    protected const DEFAULT_MAX_PER_PAGE = 10;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        $viewData = $this->executeIndexAction($request);

        return $this->view($viewData, [], '@AgentQuoteRequestPage/views/quote-request-view/quote-request-view.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setPagination($this->getPaginationTransfer($request));

        $quoteRequestCollectionTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequestOverviewCollection($quoteRequestFilterTransfer);

        return [
            'quoteRequests' => $quoteRequestCollectionTransfer->getQuoteRequests(),
            'pagination' => $quoteRequestCollectionTransfer->getPagination(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function getPaginationTransfer(Request $request): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($request->query->getInt(static::PARAM_PAGE, static::DEFAULT_PAGE))
            ->setMaxPerPage(static::DEFAULT_MAX_PER_PAGE);
    }
}
