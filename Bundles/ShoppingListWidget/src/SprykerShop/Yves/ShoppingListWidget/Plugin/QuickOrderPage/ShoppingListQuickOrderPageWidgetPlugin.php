<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\QuickOrderPage\Dependency\Plugin\ShoppingListWidget\ShoppingListWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListQuickOrderPageWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->addShoppingListWidgetVisibility();
        $this->addShoppingListOptions();
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
    protected function addShoppingListWidgetVisibility(): void
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        $this->addParameter(
            'isVisible',
            $customerTransfer && $customerTransfer->getCompanyUserTransfer()
            && $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()
        );
    }

    /**
     * @return void
     */
    protected function addShoppingListOptions(): void
    {
        $this->addParameter('shoppingListOptions', $this->getShoppingListOptions());
    }

    /**
     * @return string[]
     */
    protected function getShoppingListOptions(): array
    {
        $shoppingListOptions = [];

        if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return $shoppingListOptions;
        }

        $shoppingListCollection = $this->getFactory()->getShoppingListClient()->getCustomerShoppingListCollection();

        foreach ($shoppingListCollection->getShoppingLists() as $offset => $shoppingList) {
            if ($this->can('WriteShoppingListPermissionPlugin', $shoppingList->getIdShoppingList())) {
                $shoppingListOptions[] = [
                    'value' => $shoppingList->getIdShoppingList(),
                    'label' => $shoppingList->getName(),
                ];
            }
        }

        return $shoppingListOptions;
    }
}
