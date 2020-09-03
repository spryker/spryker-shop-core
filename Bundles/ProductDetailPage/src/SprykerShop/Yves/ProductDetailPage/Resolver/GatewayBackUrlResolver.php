<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Resolver;

use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use SprykerShop\Yves\ProductDetailPage\Dependency\Client\ProductDetailPageToProductStorageClientInterface;

class GatewayBackUrlResolver implements GatewayBackUrlResolverInterface
{
    protected const MAPPING_TYPE_SKU = 'sku';
    protected const FALLBACK_ROUTE = 'cart'; // TODO: replace to correct one

    /**
     * @var \SprykerShop\Yves\ProductDetailPage\Dependency\Client\ProductDetailPageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerShop\Yves\ProductDetailPage\Dependency\Client\ProductDetailPageToProductStorageClientInterface $productStorageClient
     */
    public function __construct(ProductDetailPageToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return string
     */
    public function resolveBackUrl(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): string
    {
        $productConfiguratorResponseTransfer->requireSku();

        $productConcreteStorageData = $this->productStorageClient->findProductConcreteStorageDataByMappingForCurrentLocale(
            static::MAPPING_TYPE_SKU,
            $productConfiguratorResponseTransfer->getSku()
        );

        return $productConcreteStorageData ? $productConcreteStorageData['url'] : static::FALLBACK_ROUTE;
    }
}
