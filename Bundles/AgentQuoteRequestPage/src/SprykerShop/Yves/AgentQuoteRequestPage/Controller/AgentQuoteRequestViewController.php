<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Controller;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
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
    protected const PARAM_QUOTE_REQUEST_VERSION_REFERENCE = 'quote-request-version-reference';

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
     * @param string $quoteRequestReference
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function detailsAction(Request $request, string $quoteRequestReference): View
    {
        $viewData = $this->executeDetailsAction($request, $quoteRequestReference);

        return $this->view($viewData, [], '@AgentQuoteRequestPage/views/quote-request-details/quote-request-details.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setWithHidden(true)
            ->setPagination($this->getPaginationTransfer($request));

        $quoteRequestCollectionTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);

        return [
            'quoteRequests' => $quoteRequestCollectionTransfer->getQuoteRequests(),
            'pagination' => $quoteRequestCollectionTransfer->getPagination(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $quoteRequestReference
     *
     * @return array
     */
    protected function executeDetailsAction(Request $request, string $quoteRequestReference): array
    {
        $quoteRequestTransfer = $this->getQuoteRequestTransferByReference($quoteRequestReference);
        $agentQuoteRequestClient = $this->getFactory()->getAgentQuoteRequestClient();

        $quoteRequestVersionTransfer = $this->findQuoteRequestVersion(
            $quoteRequestTransfer,
            $request->query->get(static::PARAM_QUOTE_REQUEST_VERSION_REFERENCE)
        );

        return [
            'quoteRequest' => $quoteRequestTransfer,
            'version' => $quoteRequestVersionTransfer,
            'isQuoteRequestCancelable' => $agentQuoteRequestClient->isQuoteRequestCancelable($quoteRequestTransfer),
            'isQuoteRequestCanStartEditable' => $agentQuoteRequestClient->isQuoteRequestCanStartEditable($quoteRequestTransfer),
            'isQuoteRequestEditable' => $agentQuoteRequestClient->isQuoteRequestEditable($quoteRequestTransfer),
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

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param string|null $versionReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer|null
     */
    protected function findQuoteRequestVersion(
        QuoteRequestTransfer $quoteRequestTransfer,
        ?string $versionReference = null
    ): ?QuoteRequestVersionTransfer {
        if (!$quoteRequestTransfer->getLatestVersion() || $versionReference === $quoteRequestTransfer->getLatestVersion()->getVersionReference()) {
            return $quoteRequestTransfer->getLatestVersion();
        }

        $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterTransfer())
            ->setQuoteRequest($quoteRequestTransfer)
            ->setQuoteRequestVersionReference($versionReference);

        $quoteRequestVersionTransfers = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer)
            ->getQuoteRequestVersions()
            ->getArrayCopy();

        $quoteRequestVersionTransfer = array_shift($quoteRequestVersionTransfers);

        return $quoteRequestVersionTransfer ?? $quoteRequestTransfer->getLatestVersion();
    }
}
