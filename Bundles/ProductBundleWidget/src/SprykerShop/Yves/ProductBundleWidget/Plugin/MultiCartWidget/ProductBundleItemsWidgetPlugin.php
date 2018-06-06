<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\MultiCartWidget;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\ProductBundleWidget\ProductBundleItemsWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleItemsWidgetPlugin extends AbstractWidgetPlugin implements ProductBundleItemsWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param null|int $itemDisplayLimit
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer, ?int $itemDisplayLimit = null): void
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
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@ProductBundleWidget/views/multi-cart-items-list/multi-cart-items-list.twig';
    }
}
