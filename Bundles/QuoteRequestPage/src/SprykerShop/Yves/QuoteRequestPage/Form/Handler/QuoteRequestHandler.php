<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form\Handler;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface;

class QuoteRequestHandler implements QuoteRequestHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface $quoteClient
     */
    public function __construct(
        QuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient,
        QuoteRequestPageToQuoteClientInterface $quoteClient
    ) {
        $this->quoteRequestClient = $quoteRequestClient;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestTransfer->getLatestVersion()
            ->setQuote($this->quoteClient->getQuote());

        $quoteRequestResponseTransfer = $this->quoteRequestClient->create($quoteRequestTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccess()) {
            $this->quoteClient->clearQuote();
        }

        return $quoteRequestResponseTransfer;
    }
}
