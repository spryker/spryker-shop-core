<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class ProductConfigurationWidgetToProductConfigurationClientBridge implements ProductConfigurationWidgetToProductConfigurationClientInterface
{
    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @param \Spryker\Client\ProductConfiguration\ProductConfigurationClientInterface $productConfigurationClient
     */
    public function __construct($productConfigurationClient)
    {
        $this->productConfigurationClient = $productConfigurationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteProductConfigurationValid(QuoteTransfer $quoteTransfer): bool
    {
        return $this->productConfigurationClient->isQuoteProductConfigurationValid($quoteTransfer);
    }
}
