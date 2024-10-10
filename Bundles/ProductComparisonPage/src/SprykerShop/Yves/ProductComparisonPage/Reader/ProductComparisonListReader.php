<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductComparisonPage\Reader;

use SprykerShop\Yves\ProductComparisonPage\Dependency\Client\ProductComparisonPageToProductStorageClientInterface;

class ProductComparisonListReader implements ProductComparisonListReaderInterface
{
    /**
     * @var string
     */
    protected const MAPPING_TYPE_SKU = 'sku';

    /**
     * @var string
     */
    protected const ATTRIBUTE_MAP_KEY = 'attributeMap';

    /**
     * @var \SprykerShop\Yves\ProductComparisonPage\Dependency\Client\ProductComparisonPageToProductStorageClientInterface
     */
    protected ProductComparisonPageToProductStorageClientInterface $productStorageClient;

    /**
     * @param \SprykerShop\Yves\ProductComparisonPage\Dependency\Client\ProductComparisonPageToProductStorageClientInterface $productStorageClient
     */
    public function __construct(ProductComparisonPageToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param list<string> $skus
     * @param string $localeName
     *
     * @return list<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function getProductsCompareList(array $skus, string $localeName): array
    {
        $productViewTransfers = [];
        $productConcreteStorageDataCollection = $this->productStorageClient->getBulkProductConcreteStorageDataByMapping(
            static::MAPPING_TYPE_SKU,
            $skus,
            $localeName,
        );

        foreach ($productConcreteStorageDataCollection as $productConcreteStorageData) {
            $productConcreteStorageData[static::ATTRIBUTE_MAP_KEY] = [];
            $productViewTransfers[] = $this->productStorageClient->mapProductStorageData(
                $productConcreteStorageData,
                $localeName,
            );
        }

        return $productViewTransfers;
    }
}
