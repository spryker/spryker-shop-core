<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductConfiguratorGatewayPageToProductConfigurationStorageClientBridge implements ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface
     */
    protected $productConfigurationStorageClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface $productConfigurationStorageClient
     */
    public function __construct($productConfigurationStorageClient)
    {
        $this->productConfigurationStorageClient = $productConfigurationStorageClient;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(
        string $sku
    ): ?ProductConfigurationInstanceTransfer {
        return $this->productConfigurationStorageClient->findProductConfigurationInstanceBySku($sku);
    }

    /**
     * @param string $groupKey
     * @param string $sku
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceInQuote(
        string $groupKey,
        string $sku,
        QuoteTransfer $quoteTransfer
    ): ?ProductConfigurationInstanceTransfer {
        return $this->productConfigurationStorageClient->findProductConfigurationInstanceInQuote($groupKey, $sku, $quoteTransfer);
    }
}
