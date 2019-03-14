<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Handler;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToAgentQuoteRequestClientInterface;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCartClientInterface;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToQuoteRequestClientInterface;

class AgentQuoteRequestCartHandler implements AgentQuoteRequestCartHandlerInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @var \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @var \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToAgentQuoteRequestClientInterface
     */
    protected $agentQuoteRequestClient;

    /**
     * @param \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToQuoteRequestClientInterface $quoteRequestClient
     * @param \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToAgentQuoteRequestClientInterface $agentQuoteRequestClient
     */
    public function __construct(
        AgentQuoteRequestWidgetToCartClientInterface $cartClient,
        AgentQuoteRequestWidgetToQuoteRequestClientInterface $quoteRequestClient,
        AgentQuoteRequestWidgetToAgentQuoteRequestClientInterface $agentQuoteRequestClient
    ) {
        $this->cartClient = $cartClient;
        $this->quoteRequestClient = $quoteRequestClient;
        $this->agentQuoteRequestClient = $agentQuoteRequestClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(): QuoteRequestResponseTransfer
    {
        $quoteTransfer = $this->cartClient->getQuote();

        if (!$quoteTransfer->getQuoteRequestReference()) {
            return $this->getErrorResponse();
        }

        $quoteRequestTransfer = $this->agentQuoteRequestClient
            ->findQuoteRequestByReference($quoteTransfer->getQuoteRequestReference());

        if (!$quoteRequestTransfer) {
            return $this->getErrorResponse();
        }

        $quoteRequestTransfer->setQuoteInProgress($quoteTransfer);

        return $this->quoteRequestClient->updateQuoteRequest($quoteRequestTransfer);
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
