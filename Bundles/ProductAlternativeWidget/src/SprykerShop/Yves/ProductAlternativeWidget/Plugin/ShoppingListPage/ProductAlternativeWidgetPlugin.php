<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget\Plugin\ShoppingListPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductAlternativeWidget\Widget\ShoppingListProductAlternativeWidget;
use SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductAlternativeWidget\ProductAlternativeWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductAlternativeWidget\Widget\ShoppingListProductAlternativeWidget instead.
 *
 * @method \SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeWidgetFactory getFactory()
 */
class ProductAlternativeWidgetPlugin extends AbstractWidgetPlugin implements ProductAlternativeWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, ShoppingListTransfer $shoppingListTransfer): void
    {
        $widget = new ShoppingListProductAlternativeWidget($productViewTransfer, $shoppingListTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return ShoppingListProductAlternativeWidget::getTemplate();
    }
}
