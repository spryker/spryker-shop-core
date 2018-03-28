<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class ShoppingListPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_SHOPPING_LIST_OVERVIEW = 'shopping-list/overview';
    public const ROUTE_SHOPPING_LIST_UPDATE = 'shopping-list/update';
    public const ROUTE_SHOPPING_LIST_DELETE = 'shopping-list/delete';
    public const ROUTE_SHOPPING_LIST_DETAILS = 'shopping-list/details';
    public const ROUTE_ADD_ITEM = 'shopping-list/add-item';
    public const ROUTE_REMOVE_ITEM = 'shopping-list/remove-item';
    public const ROUTE_ADD_TO_CART = 'shopping-list/add-to-cart';
    public const ROUTE_ADD_ALL_AVAILABLE_TO_CART = 'shopping-list/add-all-available-to-cart';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{shoppingList}', static::ROUTE_SHOPPING_LIST_OVERVIEW, 'ShoppingListPage', 'ShoppingListOverview')
            ->assert('shopping-list', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        $this->createController('/{shoppingList}/update/{shoppingListName}', static::ROUTE_SHOPPING_LIST_UPDATE, 'ShoppingListPage', 'ShoppingListOverview', 'update')
            ->assert('shopping-list', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('shoppingListName', '.+');

        $this->createGetController('/{shoppingList}/delete/{shoppingListName}', static::ROUTE_SHOPPING_LIST_DELETE, 'ShoppingListPage', 'ShoppingListOverview', 'delete')
            ->assert('shopping-list', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('shoppingListName', '.+');

        $this->createGetController('/{shoppingList}/details/{shoppingListName}', static::ROUTE_SHOPPING_LIST_DETAILS, 'ShoppingListPage', 'ShoppingList')
            ->assert('shopping-list', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('shoppingListName', '.+');

        $this->createPostController('/{shoppingList}/add-item', static::ROUTE_ADD_ITEM, 'ShoppingListPage', 'ShoppingList', 'addItem')
            ->assert('shopping-list', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        $this->createPostController('/{shoppingList}/remove-item', static::ROUTE_REMOVE_ITEM, 'ShoppingListPage', 'ShoppingList', 'removeItem')
            ->assert('shopping-list', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        $this->createPostController('/{shoppingList}/add-to-cart', static::ROUTE_ADD_TO_CART, 'ShoppingListPage', 'ShoppingList', 'addToCart')
            ->assert('shopping-list', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('sku', '[a-zA-Z0-9-_]+');

        $this->createPostController('/{shoppingList}/add-all-available-to-cart/{shoppingListName}', static::ROUTE_ADD_ALL_AVAILABLE_TO_CART, 'ShoppingListPage', 'ShoppingList', 'addAllAvailableToCart')
            ->assert('shopping-list', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('shoppingListName', '.+');
    }
}
