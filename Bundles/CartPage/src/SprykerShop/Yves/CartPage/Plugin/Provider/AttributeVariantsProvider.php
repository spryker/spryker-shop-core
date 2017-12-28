<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CartPage\Plugin\Provider;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartVariantAttributeMapperPluginInterface;
use SprykerShop\Yves\CartPage\Handler\CartItemHandlerInterface;

class AttributeVariantsProvider
{

    /**
     * @var CartVariantAttributeMapperPluginInterface
     */
    protected $cartVariantAttributeMapperPlugin;

    /**
     * @var \SprykerShop\Yves\CartPage\Handler\CartItemHandlerInterface
     */
    protected $cartItemHandler;

    /**
     * AttributeVariantsProvider constructor.
     *
     * @param CartVariantAttributeMapperPluginInterface $cartVariantAttributeMapperPlugin
     * @param CartItemHandlerInterface $cartItemHandler
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
    public function getItemsAttributes(QuoteTransfer $quoteTransfer, $localeName, array $itemAttributes = null)
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
     * @return true
     */
    public function tryToReplaceItem($sku, $quantity, $selectedAttributes, ArrayObject $items, $groupKey = null, $optionValueIds = [], $localeName)
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
            StorageProductTransfer::SELECTED_ATTRIBUTES => [$sku => $this->arrayRemoveEmpty($selectedAttributes)],
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
