<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductAlternativeWidget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductAlternativeWidget\Widget\ShoppingListProductAlternativeWidget instead.
 */
interface ProductAlternativeWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductAlternativeWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, ShoppingListTransfer $shoppingListTransfer): void;
}
