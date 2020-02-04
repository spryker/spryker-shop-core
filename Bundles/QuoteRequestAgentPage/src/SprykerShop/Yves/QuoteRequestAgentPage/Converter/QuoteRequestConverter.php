<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Converter;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface;

class QuoteRequestConverter implements QuoteRequestConverterInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART = 'quote_request_page.quote_request.converted_to_cart';

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface
     */
    protected $quoteRequestAgentClient;

    /**
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface $messengerClient
     * @param \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface $quoteRequestAgentClient
     */
    public function __construct(
        QuoteRequestAgentPageToMessengerClientInterface $messengerClient,
        QuoteRequestAgentPageToQuoteRequestAgentClientInterface $quoteRequestAgentClient
    ) {
        $this->messengerClient = $messengerClient;
        $this->quoteRequestAgentClient = $quoteRequestAgentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    protected function handleImpersonationConvertQuoteResponseMessages(QuoteResponseTransfer $quoteResponseTransfer): void
    {
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->messengerClient->addSuccessMessage(static::GLOSSARY_KEY_QUOTE_REQUEST_CONVERTED_TO_CART);
        }

        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $this->messengerClient->addErrorMessage($quoteErrorTransfer->getMessage());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function convertQuoteRequestToQuote(QuoteRequestTransfer $quoteRequestTransfer, QuoteTransfer $quoteTransfer): void
    {
        if ($quoteTransfer->getQuoteRequestReference() !== $quoteRequestTransfer->getQuoteRequestReference()) {
            $quoteResponseTransfer = $this->quoteRequestAgentClient->convertQuoteRequestToQuote($quoteRequestTransfer);

            $this->handleImpersonationConvertQuoteResponseMessages($quoteResponseTransfer);
        }
    }
}
