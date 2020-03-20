<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Widget;

use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class AddItemsToShoppingListWidget extends AbstractWidget
{
    use PermissionAwareTrait;

    protected const PARAM_IS_VISIBLE = 'isVisible';
    protected const PARAM_SHOPPING_LIST_OPTIONS = 'shoppingListOptions';
    protected const PARAM_SHOPPING_LIST_OPTION_VALUE = 'value';
    protected const PARAM_SHOPPING_LIST_OPTION_LABEL = 'label';

    /**
     * Specification:
     * - Only visible when logged in customer has a company user.
     * - Displays available shopping lists.
     * - Displays "add the shopping list" button.
     */
    public function __construct()
    {
        $this->addIsVisibleParameter();
        $this->addShoppingListOptionsParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AddItemsToShoppingListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/shopping-list-add-items/shopping-list-add-items.twig';
    }

    /**
     * @return void
     */
    protected function addIsVisibleParameter(): void
    {
        $this->addParameter(static::PARAM_IS_VISIBLE, $this->isVisible());
    }

    /**
     * @return bool
     */
    protected function isVisible(): bool
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer) {
            return false;
        }

        if (!$customerTransfer->getCompanyUserTransfer()) {
            return false;
        }

        if (!$customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    protected function addShoppingListOptionsParameter(): void
    {
        $this->addParameter(static::PARAM_SHOPPING_LIST_OPTIONS, $this->getShoppingListOptions());
    }

    /**
     * @uses \Spryker\Client\ShoppingList\Plugin\WriteShoppingListPermissionPlugin
     *
     * @return array
     */
    protected function getShoppingListOptions(): array
    {
        if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return [];
        }

        $shoppingListCollection = $this->getFactory()->getShoppingListSessionClient()->getCustomerShoppingListCollection();

        $shoppingListOptions = [];
        foreach ($shoppingListCollection->getShoppingLists() as $shoppingList) {
            if (!$this->can('WriteShoppingListPermissionPlugin', $shoppingList->getIdShoppingList())) {
                continue;
            }

            $shoppingListOptions[] = [
                static::PARAM_SHOPPING_LIST_OPTION_VALUE => $shoppingList->getIdShoppingList(),
                static::PARAM_SHOPPING_LIST_OPTION_LABEL => $shoppingList->getName(),
            ];
        }

        return $shoppingListOptions;
    }
}
