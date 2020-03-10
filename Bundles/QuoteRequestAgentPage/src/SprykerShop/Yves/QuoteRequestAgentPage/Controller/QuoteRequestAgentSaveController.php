<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 */
class QuoteRequestAgentSaveController extends QuoteRequestAgentAbstractController
{
    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_CART
     */
    protected const ROUTE_CHECKOUT = 'cart';

    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        if (!$quoteTransfer->getQuoteRequestReference()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS);

            return $this->redirectResponseInternal(static::ROUTE_CHECKOUT);
        }

        $quoteRequestTransfer = $this->getFactory()
            ->getQuoteRequestAgentClient()
            ->findQuoteRequestByReference($quoteTransfer->getQuoteRequestReference());

        if (!$quoteRequestTransfer) {
            if (!$quoteTransfer->getQuoteRequestReference()) {
                $this->addErrorMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS);

                return $this->redirectResponseInternal(static::ROUTE_CHECKOUT);
            }
        }

        $quoteRequestTransfer->getLatestVersion()->setQuote($quoteTransfer);

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestAgentClient()
            ->updateQuoteRequest($quoteRequestTransfer);

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->redirectResponseInternal(static::ROUTE_CHECKOUT);
        }

        $this->reloadQuoteForCustomer();

        return $this->redirectResponseInternal(static::ROUTE_QUOTE_REQUEST_AGENT_EDIT, [
            static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestResponseTransfer->getQuoteRequest()->getQuoteRequestReference(),
        ]);
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
