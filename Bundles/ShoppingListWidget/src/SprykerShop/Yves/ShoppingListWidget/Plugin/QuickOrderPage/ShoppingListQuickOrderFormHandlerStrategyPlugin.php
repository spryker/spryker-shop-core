<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\QuickOrderPage;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormHandlerStrategyPluginInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListQuickOrderFormHandlerStrategyPlugin extends AbstractPlugin implements QuickOrderFormHandlerStrategyPluginInterface
{
    /**
     * @see \SprykerShop\Yves\ShoppingListPage\Plugin\Provider\ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS
     */
    protected const ROUTE_SHOPPING_LIST_DETAILS = 'shopping-list/details';
    protected const ID_SHOPPING_LIST = 'idShoppingList';

    /**
     * {@inheritdoc}
     *  - Returns true is add to shopping list button was used .
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormInterface $quickOrderForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isApplicable(FormInterface $quickOrderForm, Request $request): bool
    {
        return $quickOrderForm->isSubmitted() && $request->get('addToShoppingList') !== null;
    }

    /**
     * {@inheritdoc}
     *  - Adds products to shopping list.
     *  - Returns true if all items were successfully added.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormInterface $quickOrderForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    public function execute(FormInterface $quickOrderForm, Request $request): ?RedirectResponse
    {
        /** @var \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer */
        $quickOrderTransfer = $quickOrderForm->getData();
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference($customer->getCustomerReference())
            ->setIdCompanyUser($customer->getCompanyUserTransfer()->getIdCompanyUser())
            ->setItems($this->mapShoppingListItems($quickOrderTransfer->getItems()))
            ->setIdShoppingList((int)$request->get(self::ID_SHOPPING_LIST));
        $shoppingListResponseTransfer = $this->getFactory()
                    ->getShoppingListClient()
                    ->addItems($shoppingListTransfer);
        if (!$shoppingListResponseTransfer->getIsSuccess()) {
            return null;
        }

        return $this->getFactory()
            ->createRedirectResponse(
                static::ROUTE_SHOPPING_LIST_DETAILS,
                [self::ID_SHOPPING_LIST => $shoppingListResponseTransfer->getShoppingList()->getIdShoppingList()]
            );
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
                ->setQuantity($itemTransfer->getQty()));
        }

        return $shoppingListItems;
    }
}
