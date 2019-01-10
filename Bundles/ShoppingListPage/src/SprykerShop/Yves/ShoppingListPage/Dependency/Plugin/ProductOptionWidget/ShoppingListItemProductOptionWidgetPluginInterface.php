<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductOptionWidget;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use molecule('shopping-list-item-product-option-display', 'ProductOptionWidget') instead.
 */
interface ShoppingListItemProductOptionWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ShoppingListItemProductOptionWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function initialize(ShoppingListItemTransfer $shoppingListItemTransfer): void;
}
