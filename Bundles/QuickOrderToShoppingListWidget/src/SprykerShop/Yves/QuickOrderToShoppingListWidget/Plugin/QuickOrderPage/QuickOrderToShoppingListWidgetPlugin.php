<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderToShoppingListWidget\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\QuickOrderPage\Dependency\Plugin\ShoppingListWidget\ShoppingListWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\QuickOrderToShoppingListWidget\QuickOrderToShoppingListWidgetFactory getFactory()
 */
class QuickOrderToShoppingListWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this
            ->addParameter('shoppingListCollection', $this->getShoppingListCollection());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuickOrderToShoppingListWidget/views/shopping-list-quick-order/shopping-list-quick-order.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function getShoppingListCollection(): ShoppingListCollectionTransfer
    {
        $shoppingListCollection = new ShoppingListCollectionTransfer();

        if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return $shoppingListCollection;
        }

        $shoppingListCollection = $this->getFactory()->getShoppingListClient()->getCustomerShoppingListCollection();
        $shoppingLists = $shoppingListCollection->getShoppingLists();

        foreach ($shoppingLists as $offset => $shoppingList) {
            if (!$this->can('WriteShoppingListPermissionPlugin', $shoppingList->getIdShoppingList())) {
                $shoppingLists->offsetUnset($offset);
            }
        }

        $shoppingListCollection->setShoppingLists($shoppingLists);

        return $shoppingListCollection;
    }
}
