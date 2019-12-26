<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListMenuItemWidget extends AbstractWidget
{
    protected const PAGE_KEY_SHOPPING_LIST = 'shoppingList';
    protected const PARAMETER_IS_ACTIVE_PAGE = 'isActivePage';
    protected const PARAMETER_SHOPPING_LIST_COLLECTION = 'shoppingListCollection';
    protected const PARAMETER_ACTIVE_SHOPPING_LIST_ID = 'activeShoppingListId';

    /**
     * @param string $activePage
     * @param int|null $activeEntityId
     */
    public function __construct(string $activePage, ?int $activeEntityId = null)
    {
        $this->addActivePageParameter($activePage);
        $this->addShoppingListCollectionParameter($activePage);
        $this->addActiveShoppingListIdParameter($activePage, $activeEntityId);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShoppingListMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/shopping-list-menu-item/shopping-list-menu-item.twig';
    }

    /**
     * @param string $activePage
     *
     * @return void
     */
    protected function addActivePageParameter(string $activePage): void
    {
        $this->addParameter(static::PARAMETER_IS_ACTIVE_PAGE, $this->isShoppingListPageActive($activePage));
    }

    /**
     * @param string $activePage
     *
     * @return void
     */
    protected function addShoppingListCollectionParameter(string $activePage): void
    {
        $this->addParameter(
            static::PARAMETER_SHOPPING_LIST_COLLECTION,
            $this->isShoppingListPageActive($activePage) ? $this->getCustomerShoppingListCollection() : []
        );
    }

    /**
     * @param string $activePage
     * @param int|null $activeShoppingListId
     *
     * @return void
     */
    protected function addActiveShoppingListIdParameter(string $activePage, ?int $activeShoppingListId = null): void
    {
        $this->addParameter(
            static::PARAMETER_ACTIVE_SHOPPING_LIST_ID,
            $this->isShoppingListPageActive($activePage) ? $activeShoppingListId : []
        );
    }

    /**
     * @param string $activePage
     *
     * @return bool
     */
    protected function isShoppingListPageActive(string $activePage): bool
    {
        return $activePage === static::PAGE_KEY_SHOPPING_LIST;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListTransfer[]
     */
    protected function getCustomerShoppingListCollection(): array
    {
        $customerShoppingListCollectionTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->getCustomerShoppingListCollection();

        return (array)$customerShoppingListCollectionTransfer->getShoppingLists();
    }
}
