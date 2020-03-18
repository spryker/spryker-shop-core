<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestSaveController extends QuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';
    protected const GLOSSARY_KEY_QUOTE_REQUEST_SAVED = 'quote_request_page.quote_request.saved';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Provider\QuoteRequestPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()->getCartClient()->getQuote();

        if (!$quoteTransfer->getQuoteRequestReference()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS);

            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST);
        }

        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteTransfer->getQuoteRequestReference());
        $quoteRequestTransfer->getLatestVersion()->setQuote($quoteTransfer);

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->updateQuoteRequest($quoteRequestTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_SAVED);

            $this->reloadQuoteForCustomer();

            return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_EDIT, [
                static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestResponseTransfer->getQuoteRequest()->getQuoteRequestReference(),
            ]);
        }

        return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST);
    }

    /**
     * @return void
     */
    protected function reloadQuoteForCustomer(): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer) {
            return;
        }

        $this->getFactory()
            ->getPersistentCartClient()
            ->reloadQuoteForCustomer($customerTransfer);
    }
}
