<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form\Handler;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCustomerClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToPersistentCartClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface;

class QuoteRequestHandler implements QuoteRequestHandlerInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WITH_EMPTY_CART = 'quote_request.validation.error.empty_cart';

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToPersistentCartClientInterface $persistentCartClient
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCustomerClientInterface $customerClient
     */
    public function __construct(
        QuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient,
        QuoteRequestPageToPersistentCartClientInterface $persistentCartClient,
        QuoteRequestPageToQuoteClientInterface $quoteClient,
        QuoteRequestPageToCustomerClientInterface $customerClient
    ) {
        $this->quoteRequestClient = $quoteRequestClient;
        $this->persistentCartClient = $persistentCartClient;
        $this->quoteClient = $quoteClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        if (!$quoteRequestTransfer->getLatestVersion()->getQuote()->getItems()->count()) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_WITH_EMPTY_CART);
        }

        $quoteRequestResponseTransfer = $this->quoteRequestClient->createQuoteRequest($quoteRequestTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            $this->clearQuote();
        }

        return $quoteRequestResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->quoteRequestClient->updateQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @return void
     */
    protected function clearQuote(): void
    {
        $this->quoteClient->clearQuote();
        $this->persistentCartClient->reloadQuoteForCustomer($this->customerClient->getCustomer());
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function getErrorResponse(string $message): QuoteRequestResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
