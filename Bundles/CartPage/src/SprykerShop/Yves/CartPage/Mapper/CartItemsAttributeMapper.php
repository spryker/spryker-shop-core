<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Spryker\Shared\CartVariant\CartVariantConstants;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface;

class CartItemsAttributeMapper implements CartItemsMapperInterface
{
    /**
     * @var string
     */
    public const CONCRETE_PRODUCTS_AVAILABILITY = 'concrete_products_availability';

    /**
     * @var string
     */
    public const CONCRETE_PRODUCT_AVAILABLE_ITEMS = 'concrete_product_available_items';

    /**
     * @var string
     */
    public const PRODUCT_CONCRETE_IDS = 'product_concrete_ids';

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use {@link KEY_ATTRIBUTE_VARIANT_MAP} instead.
     *
     * @var string
     */
    protected const KEY_ATTRIBUTE_VARIANTS = 'attribute_variants';

    /**
     * @var string
     */
    protected const KEY_ATTRIBUTE_MAP = 'attribute_map';

    /**
     * @var string
     */
    protected const KEY_ATTRIBUTE_VARIANT_MAP = 'attribute_variant_map';

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Mapper\CartItemsMapperInterface
     */
    protected $cartItemsAvailabilityMapper;

    /**
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface $productStorageClient
     * @param \SprykerShop\Yves\CartPage\Mapper\CartItemsMapperInterface $cartItemsAvailabilityMapper
     */
    public function __construct(
        CartPageToProductStorageClientInterface $productStorageClient,
        CartItemsMapperInterface $cartItemsAvailabilityMapper
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->cartItemsAvailabilityMapper = $cartItemsAvailabilityMapper;
    }

    /**
     * @param \ArrayObject $items
     * @param string $localeName
     *
     * @return array
     */
    public function buildMap(ArrayObject $items, $localeName)
    {
        $itemsAvailabilityMap = $this->cartItemsAvailabilityMapper->buildMap($items, $localeName);
        $availableItemsSkus = $this->getAvailableItemsSku($itemsAvailabilityMap);

        $attributes = [];

        $productAbstractIds = array_map(function (ItemTransfer $itemTransfer) {
            return $itemTransfer->getIdProductAbstract();
        }, $items->getArrayCopy());

        $abstractProductData = $this
            ->productStorageClient
            ->getBulkProductAbstractStorageDataByProductAbstractIdsAndLocaleName($productAbstractIds, $localeName);

        foreach ($items as $item) {
            if (!isset($abstractProductData[$item->getIdProductAbstract()])) {
                continue;
            }

            $attributeMap = $abstractProductData[$item->getIdProductAbstract()][static::KEY_ATTRIBUTE_MAP];
            if (isset($attributeMap[static::KEY_ATTRIBUTE_VARIANT_MAP])) {
                $attributes[$item->getSku()] = $this->buildAttributeMapWithAvailability(
                    $item,
                    $attributeMap,
                    $availableItemsSkus,
                );

                continue;
            }

            $attributes[$item->getSku()] = $this->getAttributesWithAvailability(
                $item,
                $attributeMap,
                $availableItemsSkus,
            );
        }

        return $attributes;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use {@link buildAttributeMapWithAvailability()} instead.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     * @param array $attributeMap
     * @param array $availableItemsSkus
     *
     * @return array
     */
    protected function getAttributesWithAvailability(ItemTransfer $item, array $attributeMap, array $availableItemsSkus)
    {
        $availableConcreteProductsSku = $this->getAvailableConcreteProductsSku($attributeMap);

        $productVariants = [];

        $attributeMapIterator = $this->createAttributeIterator($attributeMap);

        foreach ($attributeMapIterator as $attribute => $productConcreteId) {
            if ($attributeMapIterator->callHasChildren() === true) {
                continue;
            }

            $variantNameValue = $this->getParentNode($attributeMapIterator);
            [$variantName, $variantValue] = explode(':', $variantNameValue);

            if ($this->isVariantNotSet($variantName, $productVariants, $variantValue)) {
                $productVariants[$variantName][$variantValue][CartVariantConstants::AVAILABLE] = false;
                $productVariants[$variantName][$variantValue][CartVariantConstants::SELECTED] = false;
            }

            if ($this->isItemSkuAvailable($availableItemsSkus, $availableConcreteProductsSku, $productConcreteId)) {
                $productVariants[$variantName][$variantValue][CartVariantConstants::AVAILABLE] = true;
            }
            if ($productConcreteId === $item->getId()) {
                $productVariants[$variantName][$variantValue][CartVariantConstants::SELECTED] = true;
            }
        }

        return $productVariants;
    }

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CartPage\Mapper\CartItemsAttributeMapper::findAttributesMapByProductAbstractIds()} instead.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     * @param string $localeName
     *
     * @return array|null
     */
    protected function getAttributesMapByProductAbstract(ItemTransfer $item, $localeName)
    {
        return $this->productStorageClient->findProductAbstractStorageData($item->getIdProductAbstract(), $localeName);
    }

    /**
     * @param array $itemsAvailabilityMap
     *
     * @return array
     */
    protected function getAvailableItemsSku(array $itemsAvailabilityMap)
    {
        $availableItemsSku = [];
        foreach ($itemsAvailabilityMap as $sku => $availability) {
            if ($availability[StorageAvailabilityTransfer::CONCRETE_PRODUCT_AVAILABLE_ITEMS]) {
                $availableItemsSku[] = $sku;
            }
        }

        return $availableItemsSku;
    }

    /**
     * @param array $attributeMap
     *
     * @return array
     */
    protected function getAvailableConcreteProductsSku(array $attributeMap)
    {
        $productConcreteSkus = [];
        if (array_key_exists(static::PRODUCT_CONCRETE_IDS, $attributeMap)) {
            $productConcreteIds = $attributeMap[static::PRODUCT_CONCRETE_IDS];
            $productConcreteSkus = array_flip($productConcreteIds);
        }

        return $productConcreteSkus;
    }

    /**
     * @param array $attributeMap
     *
     * @return \RecursiveIteratorIterator
     */
    protected function createAttributeIterator(array $attributeMap)
    {
        $attributeMapIterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($attributeMap[static::KEY_ATTRIBUTE_VARIANTS]),
            RecursiveIteratorIterator::SELF_FIRST,
        );

        return $attributeMapIterator;
    }

    /**
     * @param string $variantName
     * @param array $productVariants
     * @param string $variantValue
     *
     * @return bool
     */
    protected function isVariantNotSet($variantName, array $productVariants, $variantValue)
    {
        return array_key_exists($variantName, $productVariants) === false || array_key_exists(
            $variantValue,
            $productVariants[$variantName],
        ) === false;
    }

    /**
     * @param \RecursiveIteratorIterator $attributeMapIterator
     *
     * @return string
     */
    protected function getParentNode(RecursiveIteratorIterator $attributeMapIterator)
    {
        return $attributeMapIterator->getSubIterator($attributeMapIterator->getDepth() - 1)->key();
    }

    /**
     * @param array $availableItemsSkus
     * @param array $availableConcreteProductsSku
     * @param int $productConcreteId
     *
     * @return bool
     */
    protected function isItemSkuAvailable(array $availableItemsSkus, array $availableConcreteProductsSku, $productConcreteId)
    {
        return in_array($availableConcreteProductsSku[$productConcreteId], $availableItemsSkus, true);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $attributeMap
     * @param array<string> $availableItemsSkus
     *
     * @return array
     */
    protected function buildAttributeMapWithAvailability(
        ItemTransfer $itemTransfer,
        array $attributeMap,
        array $availableItemsSkus
    ): array {
        $attributeMapWithAvailability = [];
        $availableConcreteProductsSku = $this->getAvailableConcreteProductsSku($attributeMap);
        $attributeVariantMap = $attributeMap[static::KEY_ATTRIBUTE_VARIANT_MAP];

        foreach ($attributeVariantMap as $idProductConcrete => $superAttributes) {
            $attributeMapWithAvailability = $this->setProductAvailability(
                $superAttributes,
                $attributeMapWithAvailability,
                $availableItemsSkus,
                $availableConcreteProductsSku,
                $idProductConcrete,
                $itemTransfer,
            );
        }

        return $attributeMapWithAvailability;
    }

    /**
     * @param array $superAttributes
     * @param array $attributeMapWithAvailability
     * @param array<string> $availableItemsSkus
     * @param array $availableConcreteProductsSku
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function setProductAvailability(
        array $superAttributes,
        array $attributeMapWithAvailability,
        array $availableItemsSkus,
        array $availableConcreteProductsSku,
        int $idProductConcrete,
        ItemTransfer $itemTransfer
    ): array {
        foreach ($superAttributes as $attributeName => $attributeValue) {
            if ($this->isVariantNotSet($attributeName, $attributeMapWithAvailability, $attributeValue)) {
                $attributeMapWithAvailability[$attributeName][$attributeValue][CartVariantConstants::AVAILABLE] = false;
                $attributeMapWithAvailability[$attributeName][$attributeValue][CartVariantConstants::SELECTED] = false;
            }

            if ($this->isItemSkuAvailable($availableItemsSkus, $availableConcreteProductsSku, $idProductConcrete)) {
                $attributeMapWithAvailability[$attributeName][$attributeValue][CartVariantConstants::AVAILABLE] = true;
            }

            if ($idProductConcrete === $itemTransfer->getId()) {
                $attributeMapWithAvailability[$attributeName][$attributeValue][CartVariantConstants::SELECTED] = true;
            }
        }

        return $attributeMapWithAvailability;
    }
}
