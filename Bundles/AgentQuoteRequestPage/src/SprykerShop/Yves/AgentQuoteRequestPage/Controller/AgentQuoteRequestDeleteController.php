<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 */
class AgentQuoteRequestDeleteController extends AgentQuoteRequestAbstractController
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_SUCCESS_CANCELED = 'quote_request.validation.success.canceled';

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestResponseTransfer = $this->getFactory()
            ->getAgentQuoteRequestClient()
            ->cancelQuoteRequest((new QuoteRequestCriteriaTransfer())->setQuoteRequestReference($quoteRequestReference));

        $this->processResponseMessages($quoteRequestResponseTransfer);

        return $this->redirectResponseInternal(static::ROUTE_AGENT_QUOTE_REQUEST);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return void
     */
    protected function processResponseMessages(QuoteRequestResponseTransfer $quoteRequestResponseTransfer): void
    {
        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_SUCCESS_CANCELED);

            return;
        }

        $this->handleResponseErrors($quoteRequestResponseTransfer);
    }
}
