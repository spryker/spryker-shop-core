<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Shared\CartVariant\CartVariantConstants;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToZedRequestClientInterface;

class CartItemHandler implements CartItemHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface
     */
    protected $productClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface $productClient
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(
        CartPageToCartClientInterface $cartClient,
        CartPageToProductStorageClientInterface $productClient,
        CartPageToZedRequestClientInterface $zedRequestClient
    ) {
        $this->cartClient = $cartClient;
        $this->productClient = $productClient;
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param string $sku
     * @param array $selectedAttributes
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function getProductViewTransfer($sku, array $selectedAttributes, ArrayObject $items, $localeName)
    {
        return $this->mapSelectedAttributesToStorageProduct($sku, $selectedAttributes, $items, $localeName);
    }

    /**
     * @param string $currentItemSku
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param float $quantity
     * @param string $groupKey
     * @param array $optionValueIds
     *
     * @return void
     */
    public function replaceCartItem(
        $currentItemSku,
        ProductViewTransfer $productViewTransfer,
        $quantity,
        $groupKey,
        array $optionValueIds
    ) {
        $newItemSku = $productViewTransfer->getSku();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($newItemSku);
        $itemTransfer->setQuantity($quantity);
        $this->addProductOptions($optionValueIds, $itemTransfer);

        $this->cartClient->addItem($itemTransfer);
        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();

        if (count($this->zedRequestClient->getLastResponseErrorMessages()) === 0) {
            $this->cartClient->removeItem($currentItemSku, $groupKey);
        }
    }

    /**
     * @param string $sku
     * @param array $selectedAttributes
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapSelectedAttributesToStorageProduct($sku, array $selectedAttributes, ArrayObject $items, $localeName)
    {
        foreach ($items as $item) {
            if ($item->getSku() === $sku) {
                return $this->getStorageProductForSelectedAttributes($selectedAttributes, $item, $localeName);
            }
        }

        return new ProductViewTransfer();
    }

    /**
     * @param array $selectedAttributes
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function getStorageProductForSelectedAttributes(array $selectedAttributes, $item, $localeName)
    {
        $productData = $this->productClient->findProductAbstractStorageData(
            $item->getIdProductAbstract(),
            $localeName
        );

        if ($productData === null) {
            return new ProductViewTransfer();
        }

        return $this->productClient->mapProductStorageData(
            $productData,
            $localeName,
            $selectedAttributes
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param array $itemAttributesBySku
     * @param array $selectedAttributes
     * @param string $localeName
     *
     * @return array
     */
    public function narrowDownOptions(
        ArrayObject $items,
        array $itemAttributesBySku,
        array $selectedAttributes,
        $localeName
    ) {
        if (count($selectedAttributes) === 0) {
            return $itemAttributesBySku;
        }

        foreach ($selectedAttributes as $sku => $attributes) {
            $itemAttributesBySku = $this->setSelectedAttributesAsSelected($itemAttributesBySku, $attributes, $sku);

            $availableAttributes = $this->getAvailableAttributesForItem($items, $selectedAttributes, $sku, $localeName);

            $itemAttributesBySku = $this->removeAttributesThatAreNotAvailableForItem($itemAttributesBySku, $sku, $availableAttributes);
        }

        return $itemAttributesBySku;
    }

    /**
     * @param array $itemAttributesBySku
     * @param array $attributes
     * @param string $sku
     *
     * @return array
     */
    protected function setSelectedAttributesAsSelected(array $itemAttributesBySku, array $attributes, $sku)
    {
        foreach ($attributes as $key => $attribute) {
            unset($itemAttributesBySku[$sku][$key]);
            $itemAttributesBySku[$sku][$key][$attribute][CartVariantConstants::SELECTED] = true;
            $itemAttributesBySku[$sku][$key][$attribute][CartVariantConstants::AVAILABLE] = true;
        }

        return $itemAttributesBySku;
    }

    /**
     * @param \ArrayObject $items
     * @param array $itemAttributes
     * @param string $sku
     * @param string $localeName
     *
     * @return array
     */
    protected function getAvailableAttributesForItem(ArrayObject $items, array $itemAttributes, $sku, $localeName)
    {
        $productViewTransfer = $this->getProductViewTransfer($sku, $itemAttributes[$sku], $items, $localeName);
        $availableAttributes = $productViewTransfer->getAvailableAttributes();

        return $availableAttributes;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @param array $itemAttributesBySku
     * @param string $sku
     * @param array $availableAttributes
     *
     * @return array
     */
    protected function removeAttributesThatAreNotAvailableForItem(array $itemAttributesBySku, $sku, array $availableAttributes)
    {
        foreach ($itemAttributesBySku[$sku] as $key => $attributes) {
            foreach ($attributes as $attributeName => $options) {
                if (array_key_exists($key, $availableAttributes)) {
                    if (in_array($attributeName, $availableAttributes[$key]) === false) {
                        unset($itemAttributesBySku[$sku][$key][$attributeName]);
                    }
                }
            }
        }

        return $itemAttributesBySku;
    }

    /**
     * @param array $optionValueUsageIds
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addProductOptions(array $optionValueUsageIds, ItemTransfer $itemTransfer)
    {
        foreach ($optionValueUsageIds as $idOptionValueUsage) {
            if (!$idOptionValueUsage) {
                continue;
            }

            $productOptionTransfer = new ProductOptionTransfer();
            $productOptionTransfer->setIdProductOptionValue($idOptionValueUsage);

            $itemTransfer->addProductOption($productOptionTransfer);
        }
    }
}
