<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form\Handler;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface;

class QuoteRequestHandler implements QuoteRequestHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface $cartClient
     */
    public function __construct(
        QuoteRequestPageToQuoteRequestClientInterface $quoteRequestClient,
        QuoteRequestPageToCartClientInterface $cartClient
    ) {
        $this->quoteRequestClient = $quoteRequestClient;
        $this->cartClient = $cartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestResponseTransfer = $this->quoteRequestClient
            ->create($quoteRequestTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccess()) {
            $this->clearQuote();
        }

        return $quoteRequestResponseTransfer;
    }

    /**
     * @return void
     */
    protected function clearQuote(): void
    {
        $this->cartClient->clearQuote();
        $this->cartClient->validateQuote();
    }
}
