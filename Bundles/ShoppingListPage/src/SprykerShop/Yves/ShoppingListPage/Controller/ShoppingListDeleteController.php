<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShoppingListPage\Business\SharedShoppingListReader;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShoppingListDeleteController extends AbstractShoppingListController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED = 'customer.account.shopping_list.delete.failed';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_SUCCESS = 'customer.account.shopping_list.delete.success';

    /**
     * @uses \SprykerShop\Yves\ShoppingListPage\Plugin\Router\ShoppingListPageRouteProviderPlugin::ROUTE_SHOPPING_LIST
     * @var string
     */
    protected const ROUTE_SHOPPING_LIST = 'shopping-list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idShoppingList
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, int $idShoppingList): RedirectResponse
    {
        $shoppingListDeleteForm = $this->getFactory()
            ->getShoppingListDeleteForm(new ShoppingListTransfer())
            ->handleRequest($request);

        if (!$shoppingListDeleteForm->isSubmitted() || !$shoppingListDeleteForm->isValid()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED);

            return $this->redirectResponseInternal(static::ROUTE_SHOPPING_LIST);
        }

        $shoppingListTransfer = $shoppingListDeleteForm->getData();
        $shoppingListTransfer->setIdCompanyUser(
            $this->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser(),
        );

        $shoppingListResponseTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->removeShoppingList($shoppingListTransfer);

        if (!$shoppingListResponseTransfer->getIsSuccess()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED);

            return $this->redirectResponseInternal(static::ROUTE_SHOPPING_LIST);
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_SUCCESS);

        return $this->redirectResponseInternal(static::ROUTE_SHOPPING_LIST);
    }

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

        $shoppingListDeleteForm = $this->getFactory()
            ->getShoppingListDeleteForm($shoppingListTransfer);

        return [
            'shoppingList' => $shoppingListTransfer,
            'sharedCompanyUsers' => $sharedShoppingListEntities[SharedShoppingListReader::SHARED_COMPANY_USERS],
            'sharedCompanyBusinessUnits' => $sharedShoppingListEntities[SharedShoppingListReader::SHARED_COMPANY_BUSINESS_UNITS],
            'backUrl' => $request->headers->get('referer'),
            'shoppingListDeleteForm' => $shoppingListDeleteForm->createView(),
        ];
    }
}
