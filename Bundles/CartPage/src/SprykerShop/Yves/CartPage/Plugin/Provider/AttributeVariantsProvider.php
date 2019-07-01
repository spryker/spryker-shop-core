<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\Provider;

use ArrayObject;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartVariantAttributeMapperPluginInterface;
use SprykerShop\Yves\CartPage\Handler\CartItemHandlerInterface;

class AttributeVariantsProvider
{
    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Plugin\CartVariantAttributeMapperPluginInterface
     */
    protected $cartVariantAttributeMapperPlugin;

    /**
     * @var \SprykerShop\Yves\CartPage\Handler\CartItemHandlerInterface
     */
    protected $cartItemHandler;

    /**
     * @param \SprykerShop\Yves\CartPage\Dependency\Plugin\CartVariantAttributeMapperPluginInterface $cartVariantAttributeMapperPlugin
     * @param \SprykerShop\Yves\CartPage\Handler\CartItemHandlerInterface $cartItemHandler
     */
    public function __construct(
        CartVariantAttributeMapperPluginInterface $cartVariantAttributeMapperPlugin,
        CartItemHandlerInterface $cartItemHandler
    ) {
        $this->cartVariantAttributeMapperPlugin = $cartVariantAttributeMapperPlugin;
        $this->cartItemHandler = $cartItemHandler;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     * @param array|null $itemAttributes
     *
     * @return array
     */
    public function getItemsAttributes(QuoteTransfer $quoteTransfer, $localeName, ?array $itemAttributes = null)
    {
        $itemAttributes = $this->removeEmptyAttributes($itemAttributes);

        $itemAttributesBySku = $this->cartVariantAttributeMapperPlugin
            ->buildMap($quoteTransfer->getItems(), $localeName);

        return $this->cartItemHandler
            ->narrowDownOptions($quoteTransfer->getItems(), $itemAttributesBySku, $itemAttributes, $localeName);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param array $selectedAttributes
     * @param \ArrayObject $items
     * @param string|null $groupKey
     * @param array $optionValueIds
     * @param string $localeName
     *
     * @return bool
     */
    public function tryToReplaceItem($sku, $quantity, $selectedAttributes, ArrayObject $items, $groupKey, $optionValueIds, $localeName)
    {
        $productViewTransfer = $this->cartItemHandler->getProductViewTransfer($sku, $selectedAttributes, $items, $localeName);

        if ($productViewTransfer->getIdProductConcrete()) {
            $this->cartItemHandler->replaceCartItem($sku, $productViewTransfer, $quantity, $groupKey, $optionValueIds);

            return true;
        }

        return false;
    }

    /**
     * @param string $sku
     * @param array $selectedAttributes
     *
     * @return array
     */
    public function formatUpdateActionResponse($sku, array $selectedAttributes)
    {
        return [
            ProductViewTransfer::SELECTED_ATTRIBUTES => [$sku => $this->arrayRemoveEmpty($selectedAttributes)],
        ];
    }

    /**
     * Removes empty nodes from array
     *
     * @param array $haystack
     *
     * @return array
     */
    protected function arrayRemoveEmpty(array $haystack)
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->arrayRemoveEmpty($haystack[$key]);
            }

            if (empty($haystack[$key])) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }

    /**
     * @param array $itemAttributes
     *
     * @return array
     */
    protected function removeEmptyAttributes(array $itemAttributes)
    {
        return array_filter($itemAttributes, function ($value) {
            return !empty($value);
        });
    }
}
