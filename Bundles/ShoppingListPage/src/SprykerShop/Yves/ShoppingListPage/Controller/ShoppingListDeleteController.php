<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShoppingListPage\Plugin\Provider\ShoppingListPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShoppingListDeleteController extends AbstractShoppingListController
{
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CLEAR_FAILED = 'customer.account.shopping_list.clear.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CLEAR_SUCCESS = 'customer.account.shopping_list.clear.success';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function deleteConfirmAction(Request $request): View
    {
        $response = $this->executeDeleteConfirmAction($request);

        return $this->view($response, [], '@ShoppingListPage/views/shopping-list-overview-delete/shopping-list-overview-delete.twig');
    }

    /**
     * @param int $idShoppingList
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction(int $idShoppingList)
    {
        $shoppingListTransfer = new ShoppingListTransfer();
        $shoppingListTransfer
            ->setIdShoppingList($idShoppingList)
            ->setIdCompanyUser($this->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser());

        $shoppingListResponseTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->clearShoppingList($shoppingListTransfer);

        if (!$shoppingListResponseTransfer->getIsSuccess()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CLEAR_FAILED);

            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_UPDATE, [
                static::ROUTE_PARAM_ID_SHOPPING_LIST => $idShoppingList,
            ]);
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CLEAR_SUCCESS);

        return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_UPDATE, [
            static::ROUTE_PARAM_ID_SHOPPING_LIST => $idShoppingList,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeDeleteConfirmAction(Request $request): array
    {
        $customerTransfer = $this->getCustomer();

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList((int)$request->get(static::ROUTE_PARAM_ID_SHOPPING_LIST))
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $shoppingListTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->getShoppingList($shoppingListTransfer);

        $sharedShoppingListEntities = $this->getFactory()
            ->createSharedShoppingListReader()
            ->getSharedShoppingListEntities($shoppingListTransfer, $customerTransfer);

        return [
            'shoppingList' => $shoppingListTransfer,
            'sharedCompanyUsers' => $sharedShoppingListEntities['sharedCompanyUsers'],
            'sharedCompanyBusinessUnits' => $sharedShoppingListEntities['sharedCompanyBusinessUnits'],
            'backUrl' => $request->headers->get('referer'),
        ];
    }
}
