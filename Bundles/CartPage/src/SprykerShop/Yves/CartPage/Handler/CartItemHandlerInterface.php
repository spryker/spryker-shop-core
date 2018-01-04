<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ProductViewTransfer;

interface CartItemHandlerInterface
{
    /**
     * @param string $sku
     * @param array $selectedAttributes
     * @param \ArrayObject|\Generated\Shared\Transfer\StorageProductTransfer[] $items
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
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
    public function narrowDownOptions(ArrayObject $items, array $itemAttributesBySku, array $selectedAttributes, $localeName);
}
