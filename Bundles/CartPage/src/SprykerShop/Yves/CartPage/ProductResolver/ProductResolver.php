<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\ProductResolver;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface;

class ProductResolver implements ProductResolverInterface
{
    protected const MAPPING_TYPE_SKU = 'sku';

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface $productStorageClient
     */
    public function __construct(CartPageToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcreteTransferBySku(string $sku): ProductConcreteTransfer
    {
        $productConcreteData = $this->productStorageClient
            ->findProductConcreteStorageDataByMappingForCurrentLocale(static::MAPPING_TYPE_SKU, $sku);

        $productConcreteTransfer = (new ProductConcreteTransfer())->fromArray($productConcreteData, true);

        return $productConcreteTransfer;
    }
}
