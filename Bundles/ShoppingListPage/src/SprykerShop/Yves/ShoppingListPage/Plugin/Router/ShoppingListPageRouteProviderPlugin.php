<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class ShoppingListPageRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    public const ROUTE_SHOPPING_LIST = 'shopping-list';
    public const ROUTE_SHOPPING_LIST_UPDATE = 'shopping-list/update';
    public const ROUTE_SHOPPING_LIST_DELETE_CONFIRM = 'shopping-list/delete/confirm';
    public const ROUTE_SHOPPING_LIST_DELETE = 'shopping-list/delete';
    public const ROUTE_SHOPPING_LIST_DETAILS = 'shopping-list/details';
    public const ROUTE_SHOPPING_LIST_CLEAR = 'shopping-list/clear';
    public const ROUTE_REMOVE_ITEM = 'shopping-list/remove-item';
    public const ROUTE_ADD_TO_CART = 'shopping-list/add-to-cart';
    public const ROUTE_ADD_SHOPPING_LIST_TO_CART = 'shopping-list/add-shopping-list-to-cart';
    public const ROUTE_SHOPPING_LIST_SHARE = 'shopping-list/share';
    public const ROUTE_SHOPPING_LIST_PRINT = 'shopping-list/print';
    public const ROUTE_CART_TO_SHOPPING_LIST = 'shopping-list/create-from-exist-cart';
    public const ROUTE_SHOPPING_LIST_DISMISS = 'shopping-list/dismiss';
    public const ROUTE_SHOPPING_LIST_DISMISS_CONFIRM = 'shopping-list/dismiss-confirm';
    protected const ROUTE_SHOPPING_LIST_QUICK_ADD_ITEM = 'shopping-list/quick-add-item';

    protected const SKU_PATTERN = '[a-zA-Z0-9-_\.]+';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $routeCollection = $this->addShoppingListRoute($routeCollection);
        $routeCollection = $this->addShoppingListUpdateRoute($routeCollection);
        $routeCollection = $this->addShoppingListDeleteRoute($routeCollection);
        $routeCollection = $this->addShoppingListDeleteConfirmRoute($routeCollection);
        $routeCollection = $this->addShoppingListAddToCartRoute($routeCollection);
        $routeCollection = $this->addShoppingListDetailsRoute($routeCollection);
        $routeCollection = $this->addShoppingListRemoveItemRoute($routeCollection);
        $routeCollection = $this->addShoppingListAddListsToCartRoute($routeCollection);
        $routeCollection = $this->addShoppingListShareRoute($routeCollection);
        $routeCollection = $this->addShoppingListPrintRoute($routeCollection);
        $routeCollection = $this->addCreateShoppingListFromCartRoute($routeCollection);
        $routeCollection = $this->addShoppingListClearRoute($routeCollection);
        $routeCollection = $this->addShoppingListDismissRoute($routeCollection);
        $routeCollection = $this->addShoppingListDismissConfirmRoute($routeCollection);
        $routeCollection = $this->addShoppingListQuickAddItemRoute($routeCollection);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list', 'ShoppingListPage', 'ShoppingListOverview', 'indexAction');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListUpdateRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/update/{idShoppingList}', 'ShoppingListPage', 'ShoppingListOverview', 'updateAction');
        $route = $route->assert('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_UPDATE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListDeleteRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/delete/{idShoppingList}', 'ShoppingListPage', 'ShoppingListDelete', 'deleteAction');
        $route = $route->assert('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_DELETE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListDeleteConfirmRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/delete/{idShoppingList}/confirm', 'ShoppingListPage', 'ShoppingListDelete', 'deleteConfirmAction');
        $route = $route->assert('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_DELETE_CONFIRM, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListAddListsToCartRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/add-shopping-list-to-cart', 'ShoppingListPage', 'ShoppingListOverview', 'addShoppingListToCartAction');
        $routeCollection->add(static::ROUTE_ADD_SHOPPING_LIST_TO_CART, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListDetailsRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/details/{idShoppingList}', 'ShoppingListPage', 'ShoppingList', 'indexAction');
        $route = $route->assert('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_DETAILS, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListClearRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/clear/{idShoppingList}', 'ShoppingListPage', 'ShoppingListOverview', 'clearAction');
        $route = $route->assert('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_CLEAR, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListRemoveItemRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/remove-item/{idShoppingList}/{idShoppingListItem}', 'ShoppingListPage', 'ShoppingList', 'removeItemAction');
        $route = $route->assert('idShoppingList', '\d+');
        $route = $route->assert('idShoppingListItem', '\d+');
        $routeCollection->add(static::ROUTE_REMOVE_ITEM, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListAddToCartRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/add-to-cart', 'ShoppingListPage', 'ShoppingList', 'addToCartAction');
        $routeCollection->add(static::ROUTE_ADD_TO_CART, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListShareRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/share/{idShoppingList}', 'ShoppingListPage', 'ShoppingListOverview', 'shareShoppingListAction');
        $route = $route->assert('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_SHARE, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListPrintRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/print/{idShoppingList}', 'ShoppingListPage', 'ShoppingList', 'printShoppingListAction');
        $route = $route->assert('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_PRINT, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCreateShoppingListFromCartRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/create-from-exist-cart/{idQuote}', 'ShoppingListPage', 'CartToShoppingList', 'createFromCartAction');
        $route = $route->assert('idQuote', '\d+');
        $routeCollection->add(static::ROUTE_CART_TO_SHOPPING_LIST, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListDismissRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/dismiss/{idShoppingList}', 'ShoppingListPage', 'ShoppingListDismiss', 'DismissAction');
        $route = $route->assert('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_DISMISS, $route);
        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListDismissConfirmRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/dismiss-confirm/{idShoppingList}', 'ShoppingListPage', 'ShoppingListDismiss', 'DismissConfirmAction');
        $route = $route->assert('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_DISMISS_CONFIRM, $route);
        return $routeCollection;
    }

    /**
     * @uses ShoppingListController::quickAddToShoppingListAction()
     * @uses ShoppingListPageControllerProvider::getQuantityFromRequest()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addShoppingListQuickAddItemRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/quick-add-item/{sku}', 'ShoppingListPage', 'ShoppingList', 'quickAddToShoppingListAction');
        $route = $route->assert('sku', static::SKU_PATTERN);
        $route = $route->convert('quantity', [$this, 'getQuantityFromRequest']);
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_QUICK_ADD_ITEM, $route);
        return $routeCollection;
    }

    /**
     * @param mixed $postRequestQuantityValue
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getQuantityFromRequest($postRequestQuantityValue, Request $request): int
    {
        $quantity = $request->get('quantity');

        return is_numeric($quantity) ? (int)$quantity : 1;
    }
}
