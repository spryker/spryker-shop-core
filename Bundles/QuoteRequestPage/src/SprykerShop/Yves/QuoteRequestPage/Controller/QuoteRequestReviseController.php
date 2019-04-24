<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestReviseController extends QuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_VERSION_CREATED = 'quote_request_page.quote_request_version.created';

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(string $quoteRequestReference): RedirectResponse
    {
        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->reviseQuoteRequest($quoteRequestFilterTransfer);

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_VERSION_CREATED);
        }

        return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_EDIT, [
            static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
        ]);
    }
}
