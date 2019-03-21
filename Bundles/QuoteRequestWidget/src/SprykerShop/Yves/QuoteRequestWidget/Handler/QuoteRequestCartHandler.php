<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Handler;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCartClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientInterface;

class QuoteRequestCartHandler implements QuoteRequestCartHandlerInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @var \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    public function __construct(
        QuoteRequestWidgetToCartClientInterface $cartClient,
        QuoteRequestWidgetToQuoteRequestClientInterface $quoteRequestClient
    ) {
        $this->cartClient = $cartClient;
        $this->quoteRequestClient = $quoteRequestClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequestQuote(): QuoteRequestResponseTransfer
    {
        $quoteTransfer = $this->cartClient->getQuote();

        if (!$quoteTransfer->getQuoteRequestReference()) {
            return $this->getErrorResponse();
        }

        $quoteRequestTransfer = $this->quoteRequestClient
            ->findCompanyUserQuoteRequestByReference(
                $quoteTransfer->getQuoteRequestReference(),
                $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser()
            );

        if (!$quoteRequestTransfer) {
            return $this->getErrorResponse();
        }

        $quoteRequestTransfer->setQuoteInProgress($quoteTransfer);

        return $this->quoteRequestClient->updateQuoteRequestQuote($quoteRequestTransfer);
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
