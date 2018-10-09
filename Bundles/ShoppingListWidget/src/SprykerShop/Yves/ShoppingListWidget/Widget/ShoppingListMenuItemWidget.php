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

    /**
     * @var string
     */
    protected $activePage;

    /**
     * @var int
     */
    protected $activeShoppingListId;

    /**
     * @param string $activePage
     * @param int|null $activeEntityId
     */
    public function __construct(string $activePage, ?int $activeEntityId = null)
    {
        $this->activePage = $activePage;
        $this->activeShoppingListId = $activeEntityId;

        $this->addActivePageParameter();
        $this->addShoppingListCollectionParameter();
        $this->addActiveShoppingListIdParameter();
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
     * @return void
     */
    protected function addActivePageParameter(): void
    {
        $this->addParameter('isActivePage', $this->isShoppingListPageActive());
    }

    /**
     * @return void
     */
    protected function addShoppingListCollectionParameter(): void
    {
        $this->addParameter('shoppingListCollection', $this->isShoppingListPageActive() ? $this->getCustomerShoppingListCollection() : []);
    }

    /**
     * @return void
     */
    protected function addActiveShoppingListIdParameter(): void
    {
        $this->addParameter('activeShoppingListId', $this->isShoppingListPageActive() ? $this->activeShoppingListId : []);
    }

    /**
     * @return bool
     */
    protected function isShoppingListPageActive(): bool
    {
        return $this->activePage === static::PAGE_KEY_SHOPPING_LIST;
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
