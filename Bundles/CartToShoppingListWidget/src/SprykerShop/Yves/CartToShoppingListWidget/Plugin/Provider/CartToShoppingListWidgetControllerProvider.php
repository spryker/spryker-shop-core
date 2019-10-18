<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartToShoppingListWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CartToShoppingListWidget\Plugin\Router\CartToShoppingListWidgetRouteProviderPlugin` instead.
 */
class CartToShoppingListWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_CART_TO_SHOPPING_LIST = 'shopping-list/create-from-cart';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addCreateShoppingListFromCartRoute();
    }

    /**
     * @return $this
     */
    protected function addCreateShoppingListFromCartRoute()
    {
        $this->createPostController('/{shoppingList}/create-from-cart', static::ROUTE_CART_TO_SHOPPING_LIST, 'CartToShoppingListWidget', 'CartToShoppingList')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        return $this;
    }
}
