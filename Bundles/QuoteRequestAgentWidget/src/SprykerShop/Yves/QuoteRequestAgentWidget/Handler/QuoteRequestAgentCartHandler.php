<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget\Handler;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteRequestAgentClientInterface;

class QuoteRequestAgentCartHandler implements QuoteRequestAgentCartHandlerInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteRequestAgentClientInterface
     */
    protected $quoteRequestAgentClient;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteRequestAgentClientInterface $quoteRequestAgentClient
     */
    public function __construct(
        QuoteRequestAgentWidgetToQuoteClientInterface $quoteClient,
        QuoteRequestAgentWidgetToQuoteRequestAgentClientInterface $quoteRequestAgentClient
    ) {
        $this->quoteClient = $quoteClient;
        $this->quoteRequestAgentClient = $quoteRequestAgentClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(): QuoteRequestResponseTransfer
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if (!$quoteTransfer->getQuoteRequestReference()) {
            return $this->getErrorResponse();
        }

        $quoteRequestTransfer = $this->quoteRequestAgentClient
            ->findQuoteRequestByReference($quoteTransfer->getQuoteRequestReference());

        if (!$quoteRequestTransfer) {
            return $this->getErrorResponse();
        }

        $quoteRequestTransfer->getLatestVersion()->setQuote($quoteTransfer);

        return $this->quoteRequestAgentClient->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function getErrorResponse(): QuoteRequestResponseTransfer
    {
        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage((new MessageTransfer())->setValue(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS));
    }
}
