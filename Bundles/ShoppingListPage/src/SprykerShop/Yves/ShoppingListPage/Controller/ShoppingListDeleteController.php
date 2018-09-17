<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
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

        return [
            'shoppingList' => $shoppingListTransfer,
            'sharedCompanyUsers' => $this->getSharedCompanyUsers($shoppingListTransfer, $customerTransfer),
            'sharedCompanyBusinessUnits' => $this->getSharedCompanyBusinessUnits($shoppingListTransfer, $customerTransfer),
            'backUrl' => $request->headers->get('referer'),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    protected function getSharedCompanyUsers(ShoppingListTransfer $shoppingListTransfer, CustomerTransfer $customerTransfer): ArrayObject
    {
        $sharedCompanyUserIds = [];

        foreach ($shoppingListTransfer->getSharedCompanyUsers() as $shoppingListCompanyUserTransfer) {
            $sharedCompanyUserIds[] = $shoppingListCompanyUserTransfer->getIdCompanyUser();
        }

        if (!$sharedCompanyUserIds) {
            return new ArrayObject();
        }

        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setIdCompany($customerTransfer->getCompanyUserTransfer()->getFkCompany())
            ->setCompanyUserIds($sharedCompanyUserIds);

        $companyUserTransfers = $this->getFactory()
            ->getCompanyUserClient()
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer)
            ->getCompanyUsers();

        return $companyUserTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CompanyBusinessUnitTransfer[]
     */
    protected function getSharedCompanyBusinessUnits(ShoppingListTransfer $shoppingListTransfer, CustomerTransfer $customerTransfer): ArrayObject
    {
        $sharedCompanyBusinessUnitIds = [];

        foreach ($shoppingListTransfer->getSharedCompanyBusinessUnits() as $shoppingListCompanyBusinessUnitTransfer) {
            $sharedCompanyBusinessUnitIds[] = $shoppingListCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        }

        if (!$sharedCompanyBusinessUnitIds) {
            return new ArrayObject();
        }

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany($customerTransfer->getCompanyUserTransfer()->getFkCompany())
            ->setCompanyBusinessUnitIds($sharedCompanyBusinessUnitIds);

        $companyBusinessUnitTransfers = $this->getFactory()
            ->getCompanyBusinessUnitClient()
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer)
            ->getCompanyBusinessUnits();

        return $companyBusinessUnitTransfers;
    }
}
