<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeMapper;

use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Shared\Product\ProductConfig;
use SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductAlternativeStorageClientInterface;
use SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductStorageClientInterface;

class ProductAlternativeMapper implements ProductAlternativeMapperInterface
{
    protected const PRODUCT_CONCRETE_IDS = 'product_concrete_ids';

    /**
     * @var \SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductAlternativeStorageClientInterface
     */
    protected $alternativeStorageClient;

    /**
     * @var \SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductAlternativeStorageClientInterface $alternativeStorageClient
     * @param \SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client\ProductAlternativeWidgetToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        ProductAlternativeWidgetToProductAlternativeStorageClientInterface $alternativeStorageClient,
        ProductAlternativeWidgetToProductStorageClientInterface $productStorageClient
    ) {
        $this->alternativeStorageClient = $alternativeStorageClient;
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findConcreteAlternativeProducts(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        $productReplacementForStorage = $this->alternativeStorageClient
            ->findProductAlternativeStorage($productViewTransfer->getSku());
        if (!$productReplacementForStorage) {
            return [];
        }

        $productViewTransferList = [];
        $productConcreteIds = $productReplacementForStorage->getProductConcreteIds();
        $productConcreteIds += $this->findConcreteProductIdsByAbstractProductIds(
            $productReplacementForStorage->getProductAbstractIds(),
            $localeName
        );
        foreach ($productConcreteIds as $idProduct) {
            $productViewTransferList[] = $this->getConcreteProductViewTransfer($idProduct, $localeName);
        }

        return array_filter($productViewTransferList);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findAlternativeProducts(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        $productReplacementForStorage = $this->alternativeStorageClient
            ->findProductAlternativeStorage($productViewTransfer->getSku());
        if (!$productReplacementForStorage) {
            return [];
        }

        $productViewTransferList = [];
        foreach ($productReplacementForStorage->getProductAbstractIds() as $idProduct) {
            $productViewTransferList[] = $this->getAbstractProductViewTransfer($idProduct, $localeName);
        }

        foreach ($productReplacementForStorage->getProductConcreteIds() as $idProduct) {
            $productViewTransferList[] = $this->getConcreteProductViewTransfer($idProduct, $localeName);
        }

        return array_filter($productViewTransferList);
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    protected function getConcreteProductViewTransfer(int $idProduct, string $localeName): ?ProductViewTransfer
    {
        $productConcreteStorageData = $this->productStorageClient
            ->getProductConcreteStorageData($idProduct, $localeName);
        if (empty($productConcreteStorageData)) {
            return null;
        }
        $productConcreteStorageData[ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP] = new AttributeMapStorageTransfer();

        $productViewTransfer = $this->productStorageClient
            ->mapProductStorageData($productConcreteStorageData, $localeName);

        if ($productViewTransfer->getAvailable()) {
            return $productViewTransfer;
        }

        return null;
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    protected function getAbstractProductViewTransfer(int $idProduct, string $localeName): ?ProductViewTransfer
    {
        $productAbstractStorageData = $this->productStorageClient
            ->getProductAbstractStorageData($idProduct, $localeName);
        if (empty($productAbstractStorageData)) {
            return null;
        }

        $productViewTransfer = $this->productStorageClient
            ->mapProductStorageData($productAbstractStorageData, $localeName);

        if ($productViewTransfer->getAvailable()) {
            return $productViewTransfer;
        }

        return null;
    }

    /**
     * @param int[] $abstractProductIds
     * @param string $localeName
     *
     * @return int[]
     */
    protected function findConcreteProductIdsByAbstractProductIds(array $abstractProductIds, string $localeName): array
    {
        $productConcreteIds = [];
        foreach ($abstractProductIds as $idProductAbstract) {
            $productAbstractStorageData = $this->productStorageClient
                ->getProductAbstractStorageData($idProductAbstract, $localeName);
            $productConcreteIds += $productAbstractStorageData[ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP][static::PRODUCT_CONCRETE_IDS] ?? [];
        }

        return array_values($productConcreteIds);
    }
}
