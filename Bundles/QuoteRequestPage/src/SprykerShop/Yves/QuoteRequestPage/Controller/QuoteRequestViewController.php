<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestViewController extends QuoteRequestAbstractController
{
    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(): View
    {
        $viewData = $this->executeIndexAction();

        return $this->view(
            $viewData,
            [],
            '@QuoteRequestPage/views/view-quote-request/view-quote-request.twig'
        );
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function detailsAction(string $quoteRequestReference): View
    {
        $viewData = $this->executeDetailsAction($quoteRequestReference);

        return $this->view(
            $viewData,
            [],
            '@QuoteRequestPage/views/details-quote-request/details-quote-request.twig'
        );
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
     * @param string $quoteRequestReference
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function executeDetailsAction(string $quoteRequestReference): array
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setCompanyUser($companyUserTransfer);

        $quoteRequests = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        $quoteRequestTransfer = array_shift($quoteRequests);

        if (!$quoteRequestTransfer) {
            throw new NotFoundHttpException();
        }

        return [
            'quoteRequest' => $quoteRequestTransfer,
        ];
    }
}
