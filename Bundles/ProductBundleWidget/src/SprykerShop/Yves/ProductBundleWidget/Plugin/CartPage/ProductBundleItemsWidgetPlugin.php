<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\ProductBundleWidget\ProductBundleItemsWidgetPluginInterface;
use SprykerShop\Yves\ProductBundleWidget\Widget\ProductBundleCartItemsListWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductBundleWidget\Widget\ProductBundleCartItemsListWidget instead.
 *
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleItemsWidgetPlugin extends AbstractWidgetPlugin implements ProductBundleItemsWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): void
    {
        $widget = new ProductBundleCartItemsListWidget($itemTransfer, $quoteTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return ProductBundleCartItemsListWidget::getTemplate();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getBundleItems(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): array
    {
        $groupedItems = $this->getGroupedItems($quoteTransfer);
        foreach ($groupedItems as $groupedItem) {
            if (!is_array($groupedItem)) {
                continue;
            }

            if ($groupedItem['bundleProduct']->getSku() === $itemTransfer->getSku()) {
                return $groupedItem['bundleItems'];
            }
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getGroupedItems(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->getProductBundleClient()
            ->getGroupedBundleItems($quoteTransfer->getItems(), $quoteTransfer->getBundleItems());
    }
}
