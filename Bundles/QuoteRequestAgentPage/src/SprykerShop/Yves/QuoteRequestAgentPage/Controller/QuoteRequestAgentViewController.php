<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Controller;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 */
class QuoteRequestAgentViewController extends QuoteRequestAgentAbstractController
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

        return $this->view($viewData, [], '@QuoteRequestAgentPage/views/quote-request-view/quote-request-view.twig');
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

        return $this->view($viewData, [], '@QuoteRequestAgentPage/views/quote-request-details/quote-request-details.twig');
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
        $quoteRequestTransfer = $this->getQuoteRequestByReference($quoteRequestReference);
        $quoteRequestAgentClient = $this->getFactory()->getQuoteRequestAgentClient();

        $quoteRequestVersionTransfers = $this->getQuoteRequestVersions($quoteRequestTransfer);

        $version = $this->getQuoteRequestVersion(
            $quoteRequestTransfer,
            $quoteRequestVersionTransfers,
            $request->query->get(static::PARAM_QUOTE_REQUEST_VERSION_REFERENCE)
        );

        return [
            'quoteRequest' => $quoteRequestTransfer,
            'quoteRequestVersionReferences' => $this->getQuoteRequestVersionReferences($quoteRequestVersionTransfers),
            'version' => $version,
            'isQuoteRequestCancelable' => $quoteRequestAgentClient->isQuoteRequestCancelable($quoteRequestTransfer),
            'isQuoteRequestRevisable' => $quoteRequestAgentClient->isQuoteRequestRevisable($quoteRequestTransfer),
            'isQuoteRequestEditable' => $quoteRequestAgentClient->isQuoteRequestEditable($quoteRequestTransfer),
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
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer[]
     */
    protected function getQuoteRequestVersions(QuoteRequestTransfer $quoteRequestTransfer): array
    {
        $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterTransfer())
            ->setQuoteRequest($quoteRequestTransfer);

        $quoteRequestVersionTransfers = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer)
            ->getQuoteRequestVersions()
            ->getArrayCopy();

        return $quoteRequestVersionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param array $quoteRequestVersionTransfers
     * @param string|null $versionReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function getQuoteRequestVersion(
        QuoteRequestTransfer $quoteRequestTransfer,
        array $quoteRequestVersionTransfers,
        ?string $versionReference
    ): QuoteRequestVersionTransfer {
        if (!$versionReference) {
            return $quoteRequestTransfer->getLatestVersion();
        }

        foreach ($quoteRequestVersionTransfers as $quoteRequestVersionTransfer) {
            if ($quoteRequestVersionTransfer->getVersionReference() === $versionReference) {
                return $quoteRequestVersionTransfer;
            }
        }

        throw new NotFoundHttpException();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer[] $quoteRequestVersionTransfers
     *
     * @return string[]
     */
    protected function getQuoteRequestVersionReferences(array $quoteRequestVersionTransfers): array
    {
        $versionReferences = [];

        foreach ($quoteRequestVersionTransfers as $quoteRequestVersionTransfer) {
            $versionReferences[] = $quoteRequestVersionTransfer->getVersionReference();
        }

        return $versionReferences;
    }
}
