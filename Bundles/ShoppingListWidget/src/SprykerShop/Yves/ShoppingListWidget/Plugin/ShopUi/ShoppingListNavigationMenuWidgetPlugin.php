<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\ShopUi;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShoppingListWidget\Widget\ShoppingListNavigationMenuWidget;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\ShoppingListWidget\ShoppingListWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ShoppingListWidget\Widget\ShoppingListNavigationMenuWidget instead.
 *
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListNavigationMenuWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $widget = new ShoppingListNavigationMenuWidget();

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public static function getTemplate()
    {
        return ShoppingListNavigationMenuWidget::getTemplate();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public static function getName()
    {
        return 'ShoppingListNavigationMenuWidgetPlugin';
    }
}
