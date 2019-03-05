<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget\Handler;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCartClientInterface;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToQuoteRequestClientInterface;

class AgentQuoteRequestCartHandler implements AgentQuoteRequestCartHandlerInterface
{
    protected const ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @var \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @param \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToQuoteRequestClientInterface $quoteRequestClient
     */
    public function __construct(
        AgentQuoteRequestWidgetToCartClientInterface $cartClient,
        AgentQuoteRequestWidgetToQuoteRequestClientInterface $quoteRequestClient
    ) {
        $this->cartClient = $cartClient;
        $this->quoteRequestClient = $quoteRequestClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(): QuoteRequestResponseTransfer
    {
        $quoteRequestResponseTransfer = (new QuoteRequestResponseTransfer())
            ->setIsSuccess(false)
            ->addError(static::ERROR_MESSAGE_QUOTE_REQUEST_NOT_EXISTS);

        $quoteTransfer = $this->cartClient->getQuote();

        if (!$quoteTransfer->getQuoteRequestReference()) {
            return $quoteRequestResponseTransfer;
        }

        $quoteRequestTransfer = $this->findQuoteRequest($quoteTransfer->getQuoteRequestReference());

        if (!$quoteRequestTransfer) {
            return $quoteRequestResponseTransfer;
        }

        $quoteRequestTransfer->setQuoteInProgress($quoteTransfer);

        return $this->quoteRequestClient->update($quoteRequestTransfer);
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    protected function findQuoteRequest(string $quoteRequestReference): ?QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setWithHidden(true);

        $quoteRequestTransfers = $this->quoteRequestClient
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        return array_shift($quoteRequestTransfers);
    }
}
