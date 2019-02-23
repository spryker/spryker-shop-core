<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestViewController extends QuoteRequestAbstractController
{
    protected const URL_PARAM_VERSION_REFERENCE = 'version-reference';

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
        $quoteRequestForm = $this->getFactory()->getQuoteRequestForm($request, $quoteRequestReference);

        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer */
        $quoteRequestTransfer = $quoteRequestForm->getData();

        $isQuoteRequestCancelable = $this->getFactory()
            ->getQuoteRequestClient()
            ->isQuoteRequestCancelable($quoteRequestTransfer);

        return [
            'quoteRequestForm' => $quoteRequestForm->createView(),
            'isQuoteRequestCancelable' => $isQuoteRequestCancelable,
        ];
    }
}
