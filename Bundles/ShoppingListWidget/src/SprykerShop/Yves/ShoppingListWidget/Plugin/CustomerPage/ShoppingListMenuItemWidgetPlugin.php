<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\CustomerPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\ShoppingListWidget\ShoppingListMenuItemWidgetPluginInterface;

/**
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
        $shoppingListCollection = [];
        $isActivePage = false;
        $activeShoppingListId = null;
        if ($activePage === static::PAGE_KEY_SHOPPING_LIST) {
            $isActivePage = true;
            $activeShoppingListId = $activeEntityId;
            $shoppingListCollection = $this->getCustomerShoppingListCollection();
        }
        $this->addParameter('shoppingListCollection', $shoppingListCollection)
            ->addParameter('isActivePage', $isActivePage)
            ->addParameter('activeShoppingListId', $activeShoppingListId);
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListTransfer[]
     */
    protected function getCustomerShoppingListCollection(): array
    {
        return (array)$this->getFactory()
            ->getShoppingListClient()
            ->getCustomerShoppingListCollection()
            ->getShoppingLists();
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
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
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@ShoppingListWidget/views/shopping-list-menu-item/shopping-list-menu-item.twig';
    }
}
