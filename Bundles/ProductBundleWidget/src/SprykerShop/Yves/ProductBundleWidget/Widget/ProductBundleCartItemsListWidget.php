<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleCartItemsListWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('bundleItems', $this->getBundleItems($itemTransfer, $quoteTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductBundleCartItemsListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductBundleWidget/views/cart-bundle-items-list/cart-bundle-items-list.twig';
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
