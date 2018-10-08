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
     *
     * @return void
     */
    public function initialize(string $activePage, ?int $activeEntityId = null): void
    {
        $this->activePage = $activePage;
        $this->activeShoppingListId = $activeEntityId;

        $this->addActivePageParameter();
        $this->addShoppingListCollectionParameter();
        $this->addActiveShoppingListIdParameter();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
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
