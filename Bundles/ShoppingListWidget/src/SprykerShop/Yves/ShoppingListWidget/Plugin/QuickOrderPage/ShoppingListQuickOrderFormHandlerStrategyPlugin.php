<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\QuickOrderPage;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuickOrderFormProcessResponseTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormHandlerStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListQuickOrderFormHandlerStrategyPlugin extends AbstractPlugin implements QuickOrderFormHandlerStrategyPluginInterface
{
    /**
     * @see \SprykerShop\Yves\ShoppingListPage\Plugin\Router\ShoppingListPageRouteProviderPlugin::ROUTE_SHOPPING_LIST_DETAILS
     */
    protected const ROUTE_SHOPPING_LIST_DETAILS = 'shopping-list/details';

    protected const PARAM_ID_SHOPPING_LIST = 'idShoppingList';
    protected const PARAM_ADD_TO_SHOPPING_LIST = 'addToShoppingList';

    /**
     * {@inheritDoc}
     * - Returns true if "add to shopping list" button was pressed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     * @param array $params
     *
     * @return bool
     */
    public function isApplicable(QuickOrderTransfer $quickOrderTransfer, array $params): bool
    {
        return isset($params[static::PARAM_ADD_TO_SHOPPING_LIST]);
    }

    /**
     * {@inheritDoc}
     * - Adds products to shopping list.
     * - Returns with a route if all items were successfully added.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\QuickOrderFormProcessResponseTransfer
     */
    public function execute(QuickOrderTransfer $quickOrderTransfer, array $params): QuickOrderFormProcessResponseTransfer
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $this->assertCustomerTransfer($customerTransfer);

        $shoppingListTransfer = $this->mapShoppingListTransfer($customerTransfer, $quickOrderTransfer, $params);
        $shoppingListResponseTransfer = $this->getFactory()
                    ->getShoppingListClient()
                    ->addItems($shoppingListTransfer);

        return $this->mapShoppingListResponseToQuickOrderFormProcessResponse(
            $shoppingListResponseTransfer,
            new QuickOrderFormProcessResponseTransfer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     * @param \Generated\Shared\Transfer\QuickOrderFormProcessResponseTransfer $quickOrderFormProcessResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderFormProcessResponseTransfer
     */
    protected function mapShoppingListResponseToQuickOrderFormProcessResponse(
        ShoppingListResponseTransfer $shoppingListResponseTransfer,
        QuickOrderFormProcessResponseTransfer $quickOrderFormProcessResponseTransfer
    ): QuickOrderFormProcessResponseTransfer {
        $quickOrderFormProcessResponseTransfer->fromArray($shoppingListResponseTransfer->toArray(), true);

        if (!$shoppingListResponseTransfer->getIsSuccess()) {
            return $quickOrderFormProcessResponseTransfer;
        }

        $route = $this->createRedirectRoute($shoppingListResponseTransfer);
        $quickOrderFormProcessResponseTransfer->setRoute($route);

        return $quickOrderFormProcessResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RouteTransfer
     */
    protected function createRedirectRoute(ShoppingListResponseTransfer $shoppingListResponseTransfer): RouteTransfer
    {
        return (new RouteTransfer())
            ->setRoute(static::ROUTE_SHOPPING_LIST_DETAILS)
            ->setParameters(
                [
                    static::PARAM_ID_SHOPPING_LIST => $shoppingListResponseTransfer->getShoppingList()->getIdShoppingList(),
                ]
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function assertCustomerTransfer(CustomerTransfer $customerTransfer): void
    {
        $customerTransfer
            ->requireCustomerReference()
            ->requireCompanyUserTransfer()
            ->getCompanyUserTransfer()
                ->requireIdCompanyUser();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function mapShoppingListTransfer(
        CustomerTransfer $customerTransfer,
        QuickOrderTransfer $quickOrderTransfer,
        array $params
    ): ShoppingListTransfer {
        $idShoppingList = isset($params[static::PARAM_ID_SHOPPING_LIST]) ? (int)$params[static::PARAM_ID_SHOPPING_LIST] : null;
        $shoppingListItems = $this->mapShoppingListItems($quickOrderTransfer->getItems());

        return (new ShoppingListTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser())
            ->setIdShoppingList($idShoppingList)
            ->setItems($shoppingListItems);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\QuickOrderItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShoppingListItemTransfer[]
     */
    protected function mapShoppingListItems(ArrayObject $itemTransfers): ArrayObject
    {
        $shoppingListItems = new ArrayObject();
        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getSku()) {
                continue;
            }

            $shoppingListItems->append((new ShoppingListItemTransfer())
                ->setSku($itemTransfer->getSku())
                ->setQuantity($itemTransfer->getQuantity()));
        }

        return $shoppingListItems;
    }
}
