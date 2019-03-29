<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageFactory getFactory()
 */
class AgentQuoteRequestReviseController extends AgentQuoteRequestAbstractController
{
    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(string $quoteRequestReference): RedirectResponse
    {
        $response = $this->executeIndexAction($quoteRequestReference);

        return $response;
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(string $quoteRequestReference): RedirectResponse
    {
        $quoteRequestResponseTransfer = $this->getFactory()
            ->getAgentQuoteRequestClient()
            ->reviseQuoteRequest((new QuoteRequestCriteriaTransfer())->setQuoteRequestReference($quoteRequestReference));

        $this->handleResponseErrors($quoteRequestResponseTransfer);

        return $this->redirectResponseInternal(static::ROUTE_AGENT_QUOTE_REQUEST_EDIT, [
            static::PARAM_QUOTE_REQUEST_REFERENCE => $quoteRequestReference,
        ]);
    }
}
