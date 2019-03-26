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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            'isQuoteRequestCancelable' => $quoteRequestClient->isQuoteRequestCancelable($quoteRequestTransfer),
            'isQuoteRequestReady' => $quoteRequestClient->isQuoteRequestReady($quoteRequestTransfer),
        ];
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

        if ($quoteRequestTransfer->getIsLatestVersionHidden()) {
            array_shift($quoteRequestVersionTransfers);
        }

        return $quoteRequestVersionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer[] $quoteRequestVersionTransfers
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
        foreach ($quoteRequestVersionTransfers as $quoteRequestVersionTransfer) {
            if ($quoteRequestVersionTransfer->getVersionReference() === $versionReference) {
                return $quoteRequestVersionTransfer;
            }
        }

        if (!$quoteRequestTransfer->getLatestVisibleVersion()) {
            throw new NotFoundHttpException();
        }

        return $quoteRequestTransfer->getLatestVisibleVersion();
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
