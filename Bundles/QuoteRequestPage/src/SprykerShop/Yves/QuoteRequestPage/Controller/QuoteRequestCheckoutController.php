<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 * @method \Spryker\Client\QuoteRequest\QuoteRequestClient getClient()
 */
class QuoteRequestCheckoutController extends QuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART_SUCCESS = 'quote_request.validation.converted_to_cart.success';

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function convertToCartAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestTransfer = $this->getCompanyUserQuoteRequestByReference($quoteRequestReference);

        $quoteResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->convertQuoteRequestToLockedQuote($quoteRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART_SUCCESS);
        }

        $this->handleQuoteResponseErrors($quoteResponseTransfer);

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    protected function handleQuoteResponseErrors(QuoteResponseTransfer $quoteResponseTransfer): void
    {
        foreach ($quoteResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessage());
        }
    }
}
