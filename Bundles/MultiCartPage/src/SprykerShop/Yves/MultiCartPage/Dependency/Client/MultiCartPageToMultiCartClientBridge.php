<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Dependency\Client;

use Generated\Shared\Transfer\QuoteActivatorRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class MultiCartPageToMultiCartClientBridge implements MultiCartPageToMultiCartClientInterface
{
    /**
     * @var \Spryker\Client\MultiCart\MultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @param \Spryker\Client\MultiCart\MultiCartClientInterface $multiCartClient
     */
    public function __construct($multiCartClient)
    {
        $this->multiCartClient = $multiCartClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getDefaultCart(): QuoteTransfer
    {
        return $this->multiCartClient->getDefaultCart();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteActivatorRequestTransfer $quoteActivatorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteActivatorRequestTransfer $quoteActivatorRequestTransfer): QuoteResponseTransfer
    {
        return $this->multiCartClient->setDefaultQuote($quoteActivatorRequestTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection()
    {
        return $this->multiCartClient->getQuoteCollection();
    }

    /**
     * @param string $quoteName
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteByName($quoteName): ?QuoteTransfer
    {
        return $this->multiCartClient->findQuoteByName($quoteName);
    }

    /**
     * @return bool
     */
    public function isMultiCartAllowed()
    {
        return $this->multiCartClient->isMultiCartAllowed();
    }

    /**
     * @return string
     */
    public function getDuplicatedQuoteNameSuffix()
    {
        return $this->multiCartClient->getDuplicatedQuoteNameSuffix();
    }
}
