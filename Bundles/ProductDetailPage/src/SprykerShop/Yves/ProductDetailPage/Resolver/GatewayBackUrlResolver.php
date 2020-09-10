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
        $productConfiguratorResponseTransfer->requireProductConcreteStorage();

        return $this->productStorageClient->buildProductConcreteUrl(
            $productConfiguratorResponseTransfer->getProductConcreteStorage()
        );
    }
}
