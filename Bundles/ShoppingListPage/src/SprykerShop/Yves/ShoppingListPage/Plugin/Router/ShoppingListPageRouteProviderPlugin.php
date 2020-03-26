<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ShoppingListPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_SHOPPING_LIST = 'shopping-list';
    protected const ROUTE_SHOPPING_LIST_UPDATE = 'shopping-list/update';
    protected const ROUTE_SHOPPING_LIST_DELETE_CONFIRM = 'shopping-list/delete/confirm';
    protected const ROUTE_SHOPPING_LIST_DELETE = 'shopping-list/delete';
    protected const ROUTE_SHOPPING_LIST_DETAILS = 'shopping-list/details';
    protected const ROUTE_SHOPPING_LIST_CLEAR = 'shopping-list/clear';
    protected const ROUTE_REMOVE_ITEM = 'shopping-list/remove-item';
    protected const ROUTE_ADD_TO_CART = 'shopping-list/add-to-cart';
    protected const ROUTE_ADD_SHOPPING_LIST_TO_CART = 'shopping-list/add-shopping-list-to-cart';
    protected const ROUTE_SHOPPING_LIST_SHARE = 'shopping-list/share';
    protected const ROUTE_SHOPPING_LIST_PRINT = 'shopping-list/print';
    protected const ROUTE_CART_TO_SHOPPING_LIST = 'shopping-list/create-from-exist-cart';
    protected const ROUTE_SHOPPING_LIST_DISMISS = 'shopping-list/dismiss';
    protected const ROUTE_SHOPPING_LIST_DISMISS_CONFIRM = 'shopping-list/dismiss-confirm';
    protected const ROUTE_SHOPPING_LIST_QUICK_ADD_ITEM = 'shopping-list/quick-add-item';

    protected const SKU_PATTERN = '[a-zA-Z0-9-_\.]+';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
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
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list', 'ShoppingListPage', 'ShoppingListOverview', 'indexAction');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListUpdateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/update/{idShoppingList}', 'ShoppingListPage', 'ShoppingListOverview', 'updateAction');
        $route = $route->setRequirement('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_UPDATE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListDeleteRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/delete/{idShoppingList}', 'ShoppingListPage', 'ShoppingListDelete', 'deleteAction');
        $route = $route->setRequirement('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_DELETE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListDeleteConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/delete/{idShoppingList}/confirm', 'ShoppingListPage', 'ShoppingListDelete', 'deleteConfirmAction');
        $route = $route->setRequirement('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_DELETE_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListAddListsToCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/add-shopping-list-to-cart', 'ShoppingListPage', 'ShoppingListOverview', 'addShoppingListToCartAction');
        $routeCollection->add(static::ROUTE_ADD_SHOPPING_LIST_TO_CART, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/details/{idShoppingList}', 'ShoppingListPage', 'ShoppingList', 'indexAction');
        $route = $route->setRequirement('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListClearRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/clear/{idShoppingList}', 'ShoppingListPage', 'ShoppingListOverview', 'clearAction');
        $route = $route->setRequirement('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_CLEAR, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListRemoveItemRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/remove-item/{idShoppingList}/{idShoppingListItem}', 'ShoppingListPage', 'ShoppingList', 'removeItemAction');
        $route = $route->setRequirement('idShoppingList', '\d+');
        $route = $route->setRequirement('idShoppingListItem', '\d+');
        $routeCollection->add(static::ROUTE_REMOVE_ITEM, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListAddToCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/add-to-cart', 'ShoppingListPage', 'ShoppingList', 'addToCartAction');
        $routeCollection->add(static::ROUTE_ADD_TO_CART, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListShareRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/share/{idShoppingList}', 'ShoppingListPage', 'ShoppingListOverview', 'shareShoppingListAction');
        $route = $route->setRequirement('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_SHARE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListPrintRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/print/{idShoppingList}', 'ShoppingListPage', 'ShoppingList', 'printShoppingListAction');
        $route = $route->setRequirement('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_PRINT, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCreateShoppingListFromCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/create-from-exist-cart/{idQuote}', 'ShoppingListPage', 'CartToShoppingList', 'createFromCartAction');
        $route = $route->setRequirement('idQuote', '\d+');
        $routeCollection->add(static::ROUTE_CART_TO_SHOPPING_LIST, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListDismissRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/dismiss/{idShoppingList}', 'ShoppingListPage', 'ShoppingListDismiss', 'DismissAction');
        $route = $route->setRequirement('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_DISMISS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListDismissConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/dismiss-confirm/{idShoppingList}', 'ShoppingListPage', 'ShoppingListDismiss', 'DismissConfirmAction');
        $route = $route->setRequirement('idShoppingList', '\d+');
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_DISMISS_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @uses ShoppingListController::quickAddToShoppingListAction()
     * @uses ShoppingListPageControllerProvider::getQuantityFromRequest()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShoppingListQuickAddItemRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shopping-list/quick-add-item/{sku}', 'ShoppingListPage', 'ShoppingList', 'quickAddToShoppingListAction');
        $route = $route->setRequirement('sku', static::SKU_PATTERN);
        $routeCollection->add(static::ROUTE_SHOPPING_LIST_QUICK_ADD_ITEM, $route);

        return $routeCollection;
    }
}
