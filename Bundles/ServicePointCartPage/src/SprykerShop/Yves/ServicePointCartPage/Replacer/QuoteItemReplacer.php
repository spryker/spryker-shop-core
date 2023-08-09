<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointCartPage\Replacer;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToServicePointCartClientInterface;

class QuoteItemReplacer implements QuoteItemReplacerInterface
{
    /**
     * @var \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToServicePointCartClientInterface
     */
    protected ServicePointCartPageToServicePointCartClientInterface $servicePointCartClient;

    /**
     * @param \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToServicePointCartClientInterface $servicePointCartClient
     */
    public function __construct(ServicePointCartPageToServicePointCartClientInterface $servicePointCartClient)
    {
        $this->servicePointCartClient = $servicePointCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceQuoteItems(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->servicePointCartClient->replaceQuoteItems($quoteTransfer);
    }
}
