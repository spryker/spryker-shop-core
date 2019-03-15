<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

class AgentQuoteRequestPageToAgentQuoteRequestClientBridge implements AgentQuoteRequestPageToAgentQuoteRequestClientInterface
{
    /**
     * @var \Spryker\Client\AgentQuoteRequest\AgentQuoteRequestClientInterface
     */
    protected $agentQuoteRequestClient;

    /**
     * @param \Spryker\Client\AgentQuoteRequest\AgentQuoteRequestClientInterface $agentQuoteRequestClient
     */
    public function __construct($agentQuoteRequestClient)
    {
        $this->agentQuoteRequestClient = $agentQuoteRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->agentQuoteRequestClient->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        return $this->agentQuoteRequestClient->cancelQuoteRequest($quoteRequestCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function convertQuoteRequestToQuoteInProgress(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->agentQuoteRequestClient->convertQuoteRequestToQuoteInProgress($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function markQuoteRequestInProgress(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        return $this->agentQuoteRequestClient->markQuoteRequestInProgress($quoteRequestCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToCustomer(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        return $this->agentQuoteRequestClient->sendQuoteRequestToCustomer($quoteRequestCriteriaTransfer);
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequestByReference(string $quoteRequestReference): ?QuoteRequestTransfer
    {
        return $this->agentQuoteRequestClient->findQuoteRequestByReference($quoteRequestReference);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestCancelable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->agentQuoteRequestClient->isQuoteRequestCancelable($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestCanStartEditable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->agentQuoteRequestClient->isQuoteRequestCanStartEditable($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestEditable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->agentQuoteRequestClient->isQuoteRequestEditable($quoteRequestTransfer);
    }
}
