<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CartPage\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;

interface CartItemHandlerInterface
{

    /**
     * @param string $sku
     * @param array $selectedAttributes
     * @param \ArrayObject|\Generated\Shared\Transfer\StorageProductTransfer[] $items
     * @param string $localeName
     *
     * @return ProductViewTransfer
     */
    public function getProductViewTransfer($sku, array $selectedAttributes, ArrayObject $items, $localeName);

    /**
     * @param string $currentItemSku
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param int $quantity
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
    );

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StorageProductTransfer[] $items
     * @param array $itemAttributesBySku
     * @param array|null $selectedAttributes
     * @param string $localeName
     *
     * @return array
     */
    public function narrowDownOptions(ArrayObject $items, array $itemAttributesBySku, array $selectedAttributes = null, $localeName);
}
