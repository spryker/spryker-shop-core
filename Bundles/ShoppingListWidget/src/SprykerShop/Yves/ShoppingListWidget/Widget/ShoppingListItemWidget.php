<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListItemWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isItemAvailable
     */
    public function __construct(ProductViewTransfer $productViewTransfer, bool $isItemAvailable)
    {
        $productViewTransfer = $this->expandProductViewTransfer($productViewTransfer);

        $this->addParameter('item', $productViewTransfer)
            ->addParameter('readOnly', !$isItemAvailable);
        $this->setQuantityRestrictions($productViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function expandProductViewTransfer(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $productConcreteExpanderPlugins = $this->getFactory()
            ->getProductConcreteExpanderPlugins();

        foreach ($productConcreteExpanderPlugins as $productConcreteExpanderPlugin) {
            $productViewTransfer = $productConcreteExpanderPlugin->expand($productViewTransfer);
        }

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function setQuantityRestrictions(ProductViewTransfer $productViewTransfer): void
    {
        $minQuantity = $productViewTransfer->getQuantityMin() ?? 1;
        $maxQuantity = $productViewTransfer->getQuantityMax();
        $quantityInterval = $productViewTransfer->getQuantityInterval() ?? 1;

        $this->addParameter('minQuantity', $minQuantity)
            ->addParameter('maxQuantity', $maxQuantity)
            ->addParameter('quantityInterval', $quantityInterval);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShoppingListItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/shopping-list-item/shopping-list-item.twig';
    }
}
