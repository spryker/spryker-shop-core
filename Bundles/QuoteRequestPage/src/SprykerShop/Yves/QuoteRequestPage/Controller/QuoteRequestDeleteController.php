<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestDeleteController extends QuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_SUCCESS_CANCELED = 'quote_request.validation.success.canceled';

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setCompanyUser($this->getFactory()->getCompanyUserClient()->findCompanyUser());

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->cancelByReference($quoteRequestFilterTransfer);

        $this->processResponseMessages($quoteRequestResponseTransfer);

        return $this->redirectResponseInternal(QuoteRequestPageControllerProvider::ROUTE_QUOTE_REQUEST);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return void
     */
    protected function processResponseMessages(QuoteRequestResponseTransfer $quoteRequestResponseTransfer): void
    {
        if ($quoteRequestResponseTransfer->getIsSuccess()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_SUCCESS_CANCELED);

            return;
        }

        $this->handleResponseErrors($quoteRequestResponseTransfer);
    }
}
