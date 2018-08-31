<?php

namespace  SprykerShop\Yves\QuickOrderToShoppingListWidget\Plugin\Provider;

use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class QuickOrderToShoppingListWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_QUICK_ORDER_TO_SHOPPING_LIST = 'shopping-list/create-from-quick-order';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addCreateShoppingListFromQuickOrderRoute();
    }

    /**
     * @return $this
     */
    protected function addCreateShoppingListFromQuickOrderRoute(): self
    {
        $this->createPostController('/{shoppingList}/create-from-quick-order', static::ROUTE_QUICK_ORDER_TO_SHOPPING_LIST, 'QuickOrderToShoppingListWidget', 'QuickOrderToShoppingList')
            ->assert('shoppingList', $this->getAllowedLocalesPattern() . 'shopping-list|shopping-list')
            ->value('shoppingList', 'shopping-list');

        return $this;
    }
}
