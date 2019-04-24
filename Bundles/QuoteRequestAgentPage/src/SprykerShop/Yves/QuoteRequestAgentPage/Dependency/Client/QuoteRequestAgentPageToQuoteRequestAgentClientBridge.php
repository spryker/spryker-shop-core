<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

class QuoteRequestAgentPageToQuoteRequestAgentClientBridge implements QuoteRequestAgentPageToQuoteRequestAgentClientInterface
{
    /**
     * @var \Spryker\Client\QuoteRequestAgent\QuoteRequestAgentClientInterface
     */
    protected $quoteRequestAgentClient;

    /**
     * @param \Spryker\Client\QuoteRequestAgent\QuoteRequestAgentClientInterface $quoteRequestAgentClient
     */
    public function __construct($quoteRequestAgentClient)
    {
        $this->quoteRequestAgentClient = $quoteRequestAgentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestAgentClient->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestAgentClient->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestAgentClient->cancelQuoteRequest($quoteRequestFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function reviseQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestAgentClient->reviseQuoteRequest($quoteRequestFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function convertQuoteRequestToQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->quoteRequestAgentClient->convertQuoteRequestToQuote($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function sendQuoteRequestToCustomer(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestAgentClient->sendQuoteRequestToCustomer($quoteRequestFilterTransfer);
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequestByReference(string $quoteRequestReference): ?QuoteRequestTransfer
    {
        return $this->quoteRequestAgentClient->findQuoteRequestByReference($quoteRequestReference);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestCancelable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->quoteRequestAgentClient->isQuoteRequestCancelable($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestRevisable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->quoteRequestAgentClient->isQuoteRequestRevisable($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isQuoteRequestEditable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->quoteRequestAgentClient->isQuoteRequestEditable($quoteRequestTransfer);
    }
}
