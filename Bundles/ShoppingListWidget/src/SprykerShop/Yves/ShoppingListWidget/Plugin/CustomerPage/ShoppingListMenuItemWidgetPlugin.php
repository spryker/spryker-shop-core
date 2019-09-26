<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\ShoppingListWidget\ShoppingListMenuItemWidgetPluginInterface;
use SprykerShop\Yves\ShoppingListWidget\Widget\ShoppingListMenuItemWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ShoppingListWidget\Widget\ShoppingListMenuItemWidget instead.
 *
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListMenuItemWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListMenuItemWidgetPluginInterface
{
    protected const PAGE_KEY_SHOPPING_LIST = 'shoppingList';

    /**
     * @param string $activePage
     * @param int|null $activeEntityId
     *
     * @return void
     */
    public function initialize(string $activePage, ?int $activeEntityId = null): void
    {
        $widget = new ShoppingListMenuItemWidget($activePage, $activeEntityId);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return ShoppingListMenuItemWidget::getTemplate();
    }
}
