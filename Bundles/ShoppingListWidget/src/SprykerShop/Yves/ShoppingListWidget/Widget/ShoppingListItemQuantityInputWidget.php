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
class ShoppingListItemQuantityInputWidget extends AbstractWidget
{
    public const DEFAULT_MINIMUM_QUANTITY = 1.0;
    public const DEFAULT_QUANTITY_INTERVAL = 1.0;

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isItemAvailable
     */
    public function __construct(ProductViewTransfer $productViewTransfer, bool $isItemAvailable)
    {
        $this->addParameter('item', $productViewTransfer)
            ->addParameter('readOnly', !$isItemAvailable);
        $this->setQuantityRestrictions($productViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function setQuantityRestrictions(ProductViewTransfer $productViewTransfer): void
    {
        $minQuantity = $productViewTransfer->getQuantityMin() ?? static::DEFAULT_MINIMUM_QUANTITY;
        $maxQuantity = $productViewTransfer->getQuantityMax();
        $quantityInterval = $productViewTransfer->getQuantityInterval() ?? static::DEFAULT_QUANTITY_INTERVAL;

        $this->addParameter('minQuantity', $minQuantity)
            ->addParameter('maxQuantity', $maxQuantity)
            ->addParameter('quantityInterval', $quantityInterval);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShoppingListItemQuantityInputWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/shopping-list-item/shopping-list-item.twig';
    }
}
