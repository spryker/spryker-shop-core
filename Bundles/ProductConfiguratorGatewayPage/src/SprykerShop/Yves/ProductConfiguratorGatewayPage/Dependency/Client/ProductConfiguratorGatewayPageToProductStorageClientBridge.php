<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;

class ProductConfiguratorGatewayPageToProductStorageClientBridge implements ProductConfiguratorGatewayPageToProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     */
    public function __construct($productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteStorageTransfer $productConcreteStorageTransfer
     *
     * @return string
     */
    public function buildProductConcreteUrl(ProductConcreteStorageTransfer $productConcreteStorageTransfer): string
    {
        return $this->productStorageClient->buildProductConcreteUrl($productConcreteStorageTransfer);
    }

    /**
     * @param string $mappingType
     * @param string $identifier
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMappingForCurrentLocale(string $mappingType, string $identifier): ?array
    {
        return $this->productStorageClient->findProductConcreteStorageDataByMappingForCurrentLocale($mappingType, $identifier);
    }
}
