<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Client\ProductBundleWidget\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use SprykerShop\Client\CartPage\Dependency\Plugin\CartItemTransformerPluginInterface;

/**
 * @method \SprykerShop\Client\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleCartItemTransformerPlugin extends AbstractPlugin implements CartItemTransformerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $&artItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function transformCartItems(array $cartItems, QuoteTransfer $quoteTransfer): array
    {
        $transformedCartItems = [];

        $groupedItems = $this->getGroupedItems($cartItems, $quoteTransfer);
        foreach ($groupedItems as $groupedItem) {
            if ($groupedItem instanceof ItemTransfer) {
                $transformedCartItems[] = $groupedItem;
                continue;
            }

            $transformedCartItems[] = $groupedItem['bundleProduct'];
        }

        return $transformedCartItems;
    }

    /**
     * @param array $cartItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getGroupedItems(array $cartItems, QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->getProductBundleClient()
            ->getGroupedBundleItems(new ArrayObject($cartItems), $quoteTransfer->getBundleItems());
    }
}
