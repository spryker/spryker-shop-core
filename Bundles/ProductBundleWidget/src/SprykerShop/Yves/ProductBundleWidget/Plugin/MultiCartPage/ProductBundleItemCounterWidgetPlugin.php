<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\MultiCartPage;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartPage\Dependency\Plugin\ProductBundleItemCounterWidget\ProductBundleItemCounterWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleItemCounterWidgetPlugin extends AbstractWidgetPlugin implements ProductBundleItemCounterWidgetPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter('items', $this->transformCartItems($quoteTransfer->getItems(), $quoteTransfer))
            ->addParameter('cart', $quoteTransfer);
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@ProductBundleWidget/views/multi-cart-list-items-counter/multi-cart-list-items-counter.twig';
    }
}
