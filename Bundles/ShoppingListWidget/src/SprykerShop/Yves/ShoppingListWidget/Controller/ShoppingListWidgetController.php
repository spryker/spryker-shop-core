<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Controller;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListWidgetController extends AbstractController
{
    public const PARAM_SKU = 'sku';
    public const PARAM_QUANTITY = 'quantity';
    public const PARAM_ID_SHOPPING_LIST = 'idShoppingList';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_NOT_ADDED = 'customer.account.shopping_list.item.not_added';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ADD_ITEM_SUCCESS = 'customer.account.shopping_list.add_item.success';

    /**
     * @uses \SprykerShop\Yves\ShoppingListPage\Plugin\Router\ShoppingListPageRouteProviderPlugin::ROUTE_SHOPPING_LIST
     */
    protected const ROUTE_SHOPPING_LIST = 'shopping-list';

    /**
     * @see \SprykerShop\Yves\ShoppingListPage\Plugin\Router\ShoppingListPageRouteProviderPlugin::ROUTE_SHOPPING_LIST_DETAILS
     */
    protected const ROUTE_SHOPPING_LIST_DETAILS = 'shopping-list/details';

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if ($customerTransfer === null || !$customerTransfer->getCompanyUserTransfer()) {
            throw new NotFoundHttpException('Only company users are allowed to access this page');
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        return $this->executeIndexAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request): RedirectResponse
    {
        $shoppingListItemTransfer = $this->getShoppingListItemTransferFromRequest($request);

        $shoppingListItemTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->addItem($shoppingListItemTransfer, $request->request->all());

        if (!$shoppingListItemTransfer->getIdShoppingListItem()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_NOT_ADDED);

            return $this->redirectResponseInternal(static::ROUTE_SHOPPING_LIST);
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ADD_ITEM_SUCCESS);

        if (!$shoppingListItemTransfer->getFkShoppingList()) {
            return $this->redirectResponseInternal(static::ROUTE_SHOPPING_LIST);
        }

        return $this->redirectResponseInternal(static::ROUTE_SHOPPING_LIST_DETAILS, [
            'idShoppingList' => $shoppingListItemTransfer->getFkShoppingList(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function getShoppingListItemTransferFromRequest(Request $request): ShoppingListItemTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setSku($request->get(static::PARAM_SKU))
            ->setQuantity((int)$request->get(static::PARAM_QUANTITY))
            ->setFkShoppingList($request->get(static::PARAM_ID_SHOPPING_LIST))
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        return $shoppingListItemTransfer;
    }
}
