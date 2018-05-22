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
    public const ROUTE_SHOPPING_LIST = 'shopping-list';
    public const ROUTE_SHOPPING_LIST_UPDATE = 'shopping-list/update';
    public const ROUTE_SHOPPING_LIST_DELETE = 'shopping-list/delete';
    public const ROUTE_SHOPPING_LIST_DETAILS = 'shopping-list/details';
    public const ROUTE_REMOVE_ITEM = 'shopping-list/remove-item';
    public const ROUTE_ADD_TO_CART = 'shopping-list/add-to-cart';
    public const ROUTE_MULTI_ADD_TO_CART = 'shopping-list/multi-add-to-cart';
    public const ROUTE_ADD_ALL_AVAILABLE_TO_CART = 'shopping-list/add-all-available-to-cart';
    public const ROUTE_ADD_SHOPPING_LIST_TO_CART = 'shopping-list/add-shopping-list-to-cart';
    public const ROUTE_SHOPPING_LIST_SHARE = 'shopping-list/share';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{shoppingList}', static::ROUTE_SHOPPING_LIST, 'ShoppingListPage', 'ShoppingListOverview')
            ->assert('shoppingList', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        $this->createController('/{shoppingList}/update/{idShoppingList}', static::ROUTE_SHOPPING_LIST_UPDATE, 'ShoppingListPage', 'ShoppingListOverview', 'update')
            ->assert('shoppingList', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        $this->createGetController('/{shoppingList}/delete/{idShoppingList}', static::ROUTE_SHOPPING_LIST_DELETE, 'ShoppingListPage', 'ShoppingListOverview', 'delete')
            ->assert('shoppingList', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        $this->createPostController('/{shoppingList}/add-shopping-list-to-cart', static::ROUTE_ADD_SHOPPING_LIST_TO_CART, 'ShoppingListPage', 'ShoppingListOverview', 'addShoppingListToCart')
            ->assert('shoppingList', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        $this->createGetController('/{shoppingList}/details/{idShoppingList}', static::ROUTE_SHOPPING_LIST_DETAILS, 'ShoppingListPage', 'ShoppingList')
            ->assert('shoppingList', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        $this->createGetController('/{shoppingList}/remove-item', static::ROUTE_REMOVE_ITEM, 'ShoppingListPage', 'ShoppingList', 'removeItem')
            ->assert('shoppingList', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        $this->createGetController('/{shoppingList}/add-to-cart', static::ROUTE_ADD_TO_CART, 'ShoppingListPage', 'ShoppingList', 'addToCart')
            ->assert('shoppingList', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('sku', '[a-zA-Z0-9-_]+');

        $this->createPostController('/{shoppingList}/multi-add-to-cart', static::ROUTE_MULTI_ADD_TO_CART, 'ShoppingListPage', 'ShoppingList', 'multiAddToCart')
            ->assert('shoppingList', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        $this->createPostController('/{shoppingList}/add-all-available-to-cart/{idShoppingList}', static::ROUTE_ADD_ALL_AVAILABLE_TO_CART, 'ShoppingListPage', 'ShoppingList', 'addAvailableProductsToCart')
            ->assert('shoppingList', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        $this->createController('/{shoppingList}/share/{idShoppingList}', static::ROUTE_SHOPPING_LIST_SHARE, 'ShoppingListPage', 'ShoppingListOverview', 'shareShoppingList')
            ->assert('shoppingList', $allowedLocalesPattern . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');
    }
}
