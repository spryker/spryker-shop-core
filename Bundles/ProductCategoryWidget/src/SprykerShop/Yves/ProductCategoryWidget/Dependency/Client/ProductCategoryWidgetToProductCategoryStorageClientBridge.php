<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductCategoryWidget\Dependency\Client;

class ProductCategoryWidgetToProductCategoryStorageClientBridge implements ProductCategoryWidgetToProductCategoryStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface
     */
    protected $productCategoryStorageClient;

    /**
     * @param \Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface $productCategoryStorageClient
     */
    public function __construct($productCategoryStorageClient)
    {
        $this->productCategoryStorageClient = $productCategoryStorageClient;
    }

    /**
     * @param list<int> $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return list<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer>
     */
    public function findBulkProductAbstractCategory(array $productAbstractIds, string $localeName, string $storeName): array
    {
        return $this->productCategoryStorageClient->findBulkProductAbstractCategory($productAbstractIds, $localeName, $storeName);
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     * @param string $httpReferer
     *
     * @return list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    public function filterProductCategoriesByHttpReferer(array $productCategoryStorageTransfers, string $httpReferer): array
    {
        return $this->productCategoryStorageClient->filterProductCategoriesByHttpReferer($productCategoryStorageTransfers, $httpReferer);
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    public function sortProductCategories(array $productCategoryStorageTransfers): array
    {
        return $this->productCategoryStorageClient->sortProductCategories($productCategoryStorageTransfers);
    }
}
