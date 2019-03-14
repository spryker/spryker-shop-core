<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestViewController extends QuoteRequestAbstractController
{
    protected const PARAM_QUOTE_REQUEST_VERSION_REFERENCE = 'quote-request-version-reference';

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(): View
    {
        $viewData = $this->executeIndexAction();

        return $this->view($viewData, [], '@QuoteRequestPage/views/quote-request-view/quote-request-view.twig');
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

        return $this->view($viewData, [], '@QuoteRequestPage/views/quote-request-details/quote-request-details.twig');
    }

    /**
     * @return array
     */
    protected function executeIndexAction(): array
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        $quoteRequestCollectionTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequestCollectionByFilter((new QuoteRequestFilterTransfer())->setCompanyUser($companyUserTransfer));

        return [
            'quoteRequests' => $quoteRequestCollectionTransfer->getQuoteRequests(),
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
        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteRequestReference);
        $quoteRequestClient = $this->getFactory()->getQuoteRequestClient();

        $version = $this->findQuoteRequestVersion(
            $quoteRequestTransfer,
            $request->query->get(static::PARAM_QUOTE_REQUEST_VERSION_REFERENCE)
        );

        return [
            'quoteRequest' => $quoteRequestTransfer,
            'version' => $version,
            'isQuoteRequestCancelable' => $quoteRequestClient->isQuoteRequestCancelable($quoteRequestTransfer),
            'isQuoteRequestReady' => $quoteRequestClient->isQuoteRequestReady($quoteRequestTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param string|null $versionReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer|null
     */
    protected function findQuoteRequestVersion(QuoteRequestTransfer $quoteRequestTransfer, ?string $versionReference): ?QuoteRequestVersionTransfer
    {
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
