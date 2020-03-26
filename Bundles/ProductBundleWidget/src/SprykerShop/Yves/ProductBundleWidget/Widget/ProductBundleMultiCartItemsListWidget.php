<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Widget;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleMultiCartItemsListWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int|null $itemDisplayLimit
     */
    public function __construct(QuoteTransfer $quoteTransfer, ?int $itemDisplayLimit = null)
    {
        $items = $this->transformCartItems($quoteTransfer->getItems(), $quoteTransfer);
        if (!$itemDisplayLimit) {
            $itemDisplayLimit = count($items);
        }

        $this->addParameter('items', $items)
            ->addParameter('itemDisplayLimit', $itemDisplayLimit);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function transformCartItems(ArrayObject $cartItems, QuoteTransfer $quoteTransfer): array
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
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $cartItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getGroupedItems(ArrayObject $cartItems, QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->getProductBundleClient()
            ->getGroupedBundleItems($cartItems, $quoteTransfer->getBundleItems());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductBundleMultiCartItemsListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductBundleWidget/views/multi-cart-items-list/multi-cart-items-list.twig';
    }
}
