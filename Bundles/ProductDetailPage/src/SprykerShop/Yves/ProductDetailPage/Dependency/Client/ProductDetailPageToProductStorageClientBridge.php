<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Client;

use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;

class ProductDetailPageToProductStorageClientBridge implements ProductDetailPageToProductStorageClientInterface
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
     * @param array $data
     * @param string $localeName
     * @param array $selectedAttributes
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageData(
        array $data,
        $localeName,
        array $selectedAttributes = [],
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer = null
    ) {
        return $this->productStorageClient->mapProductStorageData($data, $localeName, $selectedAttributes, $productStorageCriteriaTransfer);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool
    {
        return $this->productStorageClient->isProductAbstractRestricted($idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteRestricted(int $idProductConcrete): bool
    {
        return $this->productStorageClient->isProductConcreteRestricted($idProductConcrete);
    }
}
