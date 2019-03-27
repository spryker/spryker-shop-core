<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestReviseController extends QuoteRequestAbstractController
{
    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST_EDIT
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT = 'quote-request/edit';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexAction(string $quoteRequestReference): RedirectResponse
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new NotFoundHttpException();
        }

        $this->getFactory()
            ->getQuoteRequestClient()
            ->markQuoteRequestAsDraft(
                (new QuoteRequestCriteriaTransfer())->setQuoteRequestReference($quoteRequestReference)
                    ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            );

        return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_EDIT, [
            static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference
        ]);
    }
}
