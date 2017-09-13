<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Mapper;

use Generated\Shared\Transfer\StorageProductTransfer;
use SprykerShop\Yves\ProductDetailPage\Mapper\StorageProductMapperInterface;
use SprykerShop\Yves\ProductDetailPage\Mapper\AttributeVariantMapperInterface;
use Symfony\Component\HttpFoundation\Request;

class StorageProductMapper implements StorageProductMapperInterface
{

    /**
     * @var \SprykerShop\Yves\ProductDetailPage\Mapper\AttributeVariantMapperInterface
     */
    protected $attributeVariantMapper;

    /**
     * @var \SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\StorageProductExpanderPluginInterface[]
     */
    protected $storageProductExpanderPlugins;

    /**
     * @param \SprykerShop\Yves\ProductDetailPage\Mapper\AttributeVariantMapperInterface $attributeVariantMapper
     * @param \SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\StorageProductExpanderPluginInterface[] $storageProductExpanderPlugins
     */
    public function __construct(AttributeVariantMapperInterface $attributeVariantMapper, array $storageProductExpanderPlugins = [])
    {
        $this->attributeVariantMapper = $attributeVariantMapper;
        $this->storageProductExpanderPlugins = $storageProductExpanderPlugins;
    }

    /**
     * @param array $productData
     * @param Request $request
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProduct(array $productData, Request $request, array $selectedAttributes = [])
    {
        $storageProductTransfer = $this->mapAbstractStorageProduct($productData);
        $storageProductTransfer->setSelectedAttributes($selectedAttributes);

        $storageProductTransfer = $this->attributeVariantMapper->setSuperAttributes($storageProductTransfer);
        if (count($selectedAttributes) > 0) {
            $storageProductTransfer = $this->attributeVariantMapper->setSelectedVariants(
                $selectedAttributes,
                $storageProductTransfer
            );
        }

        foreach ($this->storageProductExpanderPlugins as $storageProductExpanderPlugin) {
            $storageProductTransfer = $storageProductExpanderPlugin->expandStorageProduct(
                $storageProductTransfer,
                $productData,
                $request
            );
        }

        return $storageProductTransfer;
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function mapAbstractStorageProduct(array $productData)
    {
        $storageProductTransfer = new StorageProductTransfer();
        $storageProductTransfer->fromArray($productData, true);

        return $storageProductTransfer;
    }

}
