<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Form\DataProvider;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig;

class QuoteRequestFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig
     */
    protected $config;

    /**
     * @param \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface $quoteClient
     * @param \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig $config
     */
    public function __construct(
        QuoteRequestPageToQuoteClientInterface $quoteClient,
        QuoteRequestPageConfig $config
    ) {
        $this->quoteClient = $quoteClient;
        $this->config = $config;
    }

    public function getData(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestTransfer
    {
        $quoteRequestTransfer->setCreatedAt((new DateTime())->format('Y-m-d H:i:s'));
        $quoteRequestTransfer->setStatus($this->config->getInitialStatus());

        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setQuote($this->quoteClient->getQuote())
            ->setVersion($this->config->getInitialVersion());

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        return $quoteRequestTransfer;
    }
}
