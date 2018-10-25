<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\ProductResolver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientInterface;

class ProductResolver implements ProductResolverInterface
{
    protected const MAPPING_TYPE_SKU = 'sku';
    protected const ID_PRODUCT_CONCRETE = 'id_product_concrete';
    protected const ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const SKU = 'sku';
    protected const NAME = 'name';

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientInterface $productStorageClient
     */
    public function __construct(QuickOrderPageToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getIdProductBySku(string $sku): int
    {
        $productConcreteData = $this->productStorageClient
            ->findProductConcreteStorageDataByMappingForCurrentLocale(static::MAPPING_TYPE_SKU, $sku);

        return $productConcreteData[static::ID_PRODUCT_CONCRETE];
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductBySku(string $sku): ProductConcreteTransfer
    {
        $productConcreteData = $this->productStorageClient
            ->findProductConcreteStorageDataByMappingForCurrentLocale(static::MAPPING_TYPE_SKU, $sku);

        return (new ProductConcreteTransfer())
            ->fromArray($productConcreteData, true);
    }

    /**
     * @param int $idProduct
     *
     * @return int
     */
    public function getIdProductAbstractByIdProduct(int $idProduct): int
    {
        $productConcreteStorageTransfers = $this->productStorageClient
            ->getProductConcreteStorageTransfers([$idProduct]);

        return $productConcreteStorageTransfers[0]->getIdProductAbstract();
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[] Keys are product SKUs
     */
    public function getProductsByQuickOrder(QuickOrderTransfer $quickOrderTransfer): array
    {
        $skus = array_map(function (QuickOrderItemTransfer $quickOrderItemTransfer) {
            return $quickOrderItemTransfer->getSku();
        }, $quickOrderTransfer->getItems()->getArrayCopy());

        $productConcreteTransfers = [];
        foreach ($skus as $sku) {
            if (empty($sku)) {
                continue;
            }

            $productConcreteTransfer = $this->findProductConcreteBySku($sku);

            if ($productConcreteTransfer !== null) {
                $productConcreteTransfers[] = $productConcreteTransfer;
            }
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithProductIds(ItemTransfer $itemTransfer): ItemTransfer
    {
        $productConcreteTransfer = $this->findProductConcreteBySku($itemTransfer->getSku());

        if ($productConcreteTransfer === null) {
            return $itemTransfer;
        }

        return $itemTransfer->setProductConcrete($productConcreteTransfer)
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function findProductConcreteBySku(string $sku): ?ProductConcreteTransfer
    {
        $productConcreteStorageData = $this->productStorageClient
            ->findProductConcreteStorageDataByMappingForCurrentLocale(static::MAPPING_TYPE_SKU, $sku);

        if ($productConcreteStorageData === null) {
            return null;
        }

        $productConcreteTransfer = (new ProductConcreteTransfer())->fromArray($productConcreteStorageData, true);
        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())->setName($productConcreteStorageData[static::NAME]);

        return $productConcreteTransfer
            ->setFkProductAbstract($productConcreteStorageData[static::ID_PRODUCT_ABSTRACT])
            ->addLocalizedAttributes($localizedAttributesTransfer);
    }
}
