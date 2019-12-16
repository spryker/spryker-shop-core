<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use `\SprykerShop\Yves\ShoppingListPage\Plugin\Router\ShoppingListPageRouteProviderPlugin` instead.
 */
class ShoppingListPageControllerProvider extends AbstractYvesControllerProvider
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
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addShoppingListRoute()
            ->addShoppingListUpdateRoute()
            ->addShoppingListDeleteRoute()
            ->addShoppingListDeleteConfirmRoute()
            ->addShoppingListAddToCartRoute()
            ->addShoppingListDetailsRoute()
            ->addShoppingListRemoveItemRoute();

        $this->addShoppingListAddListsToCartRoute()
            ->addShoppingListShareRoute()
            ->addShoppingListPrintRoute()
            ->addCreateShoppingListFromCartRoute()
            ->addShoppingListClearRoute()
            ->addShoppingListDismissRoute()
            ->addShoppingListDismissConfirmRoute()
            ->addShoppingListQuickAddItemRoute();
    }

    /**
     * @return $this
     */
    protected function addShoppingListRoute()
    {
        $this->createController('/{shoppingList}', static::ROUTE_SHOPPING_LIST, 'ShoppingListPage', 'ShoppingListOverview')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListUpdateRoute()
    {
        $this->createController('/{shoppingList}/update/{idShoppingList}', static::ROUTE_SHOPPING_LIST_UPDATE, 'ShoppingListPage', 'ShoppingListOverview', 'update')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListDeleteRoute()
    {
        $this->createGetController('/{shoppingList}/delete/{idShoppingList}', static::ROUTE_SHOPPING_LIST_DELETE, 'ShoppingListPage', 'ShoppingListDelete', 'delete')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListDeleteConfirmRoute()
    {
        $this->createController('/{shoppingList}/delete/{idShoppingList}/confirm', static::ROUTE_SHOPPING_LIST_DELETE_CONFIRM, 'ShoppingListPage', 'ShoppingListDelete', 'deleteConfirm')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListAddListsToCartRoute()
    {
        $this->createPostController('/{shoppingList}/add-shopping-list-to-cart', static::ROUTE_ADD_SHOPPING_LIST_TO_CART, 'ShoppingListPage', 'ShoppingListOverview', 'addShoppingListToCart')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListDetailsRoute()
    {
        $this->createGetController('/{shoppingList}/details/{idShoppingList}', static::ROUTE_SHOPPING_LIST_DETAILS, 'ShoppingListPage', 'ShoppingList')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListClearRoute()
    {
        $this->createGetController('/{shoppingList}/clear/{idShoppingList}', static::ROUTE_SHOPPING_LIST_CLEAR, 'ShoppingListPage', 'ShoppingListOverview', 'clear')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListRemoveItemRoute()
    {
        $this->createGetController('/{shoppingList}/remove-item/{idShoppingList}/{idShoppingListItem}', static::ROUTE_REMOVE_ITEM, 'ShoppingListPage', 'ShoppingList', 'removeItem')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+')
            ->assert('idShoppingListItem', '\d+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListAddToCartRoute()
    {
        $this->createController('/{shoppingList}/add-to-cart', static::ROUTE_ADD_TO_CART, 'ShoppingListPage', 'ShoppingList', 'addToCart')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListShareRoute()
    {
        $this->createController('/{shoppingList}/share/{idShoppingList}', static::ROUTE_SHOPPING_LIST_SHARE, 'ShoppingListPage', 'ShoppingListOverview', 'shareShoppingList')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListPrintRoute()
    {
        $this->createController('/{shoppingList}/print/{idShoppingList}', static::ROUTE_SHOPPING_LIST_PRINT, 'ShoppingListPage', 'ShoppingList', 'printShoppingList')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCreateShoppingListFromCartRoute()
    {
        $this->createController('/{shoppingList}/create-from-exist-cart/{idQuote}', static::ROUTE_CART_TO_SHOPPING_LIST, 'ShoppingListPage', 'CartToShoppingList', 'createFromCart')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idQuote', '\d+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListDismissRoute()
    {
        $this->createController('/{shoppingList}/dismiss/{idShoppingList}', static::ROUTE_SHOPPING_LIST_DISMISS, 'ShoppingListPage', 'ShoppingListDismiss', 'Dismiss')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addShoppingListDismissConfirmRoute()
    {
        $this->createController('/{shoppingList}/dismiss-confirm/{idShoppingList}', static::ROUTE_SHOPPING_LIST_DISMISS_CONFIRM, 'ShoppingListPage', 'ShoppingListDismiss', 'DismissConfirm')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('idShoppingList', '\d+');

        return $this;
    }

    /**
     * @uses ShoppingListController::quickAddToShoppingListAction()
     * @uses ShoppingListPageControllerProvider::getQuantityFromRequest()
     *
     * @return $this
     */
    protected function addShoppingListQuickAddItemRoute()
    {
        $this->createGetController('/{shoppingList}/quick-add-item/{sku}', static::ROUTE_SHOPPING_LIST_QUICK_ADD_ITEM, 'ShoppingListPage', 'ShoppingList', 'quickAddToShoppingList')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list')
            ->assert('sku', static::SKU_PATTERN)
            ->convert('quantity', [$this, 'getQuantityFromRequest']);

        return $this;
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
