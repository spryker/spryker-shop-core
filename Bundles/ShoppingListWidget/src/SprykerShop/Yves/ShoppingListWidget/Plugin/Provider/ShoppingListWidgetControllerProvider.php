<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\ShoppingListWidget\Plugin\Router\ShoppingListWidgetRouteProviderPlugin` instead.
 */
class ShoppingListWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_ADD_ITEM = 'shopping-list/add-item';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addAddItemRoute();
    }

    /**
     * @return $this
     */
    protected function addAddItemRoute()
    {
        $this->createController('/{shoppingList}/add-item', static::ROUTE_ADD_ITEM, 'ShoppingListWidget', 'ShoppingListWidget')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        return $this;
    }
}
