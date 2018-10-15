<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\QuickOrderPage\Dependency\Plugin\ShoppingListWidget\QuickOrderPageAddItemsToShoppingListWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class QuickOrderPageAddItemsToShoppingListWidgetPlugin extends AbstractWidgetPlugin implements QuickOrderPageAddItemsToShoppingListWidgetPluginInterface
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
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->addIsVisibleParameter();
        $this->addShoppingListOptionsParameter();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
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
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/shopping-list-quick-order/shopping-list-quick-order.twig';
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
     * @uses WriteShoppingListPermissionPlugin
     *
     * @return array[]
     */
    protected function getShoppingListOptions(): array
    {
        if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return [];
        }

        $shoppingListCollection = $this->getFactory()->getShoppingListClient()->getCustomerShoppingListCollection();

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
