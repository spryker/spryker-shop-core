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
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShoppingListPage\Plugin\Provider\ShoppingListPageControllerProvider;
use SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShoppingListOverviewController extends AbstractShoppingListController
{
    protected const PARAM_SHOPPING_LISTS = 'shoppingLists';
    protected const ROUTE_PARAM_ID_SHOPPING_LIST = 'idShoppingList';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_UPDATED = 'customer.account.shopping_list.updated';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED = 'customer.account.shopping_list.delete.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_SUCCESS = 'customer.account.shopping_list.delete.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CLEAR_FAILED = 'customer.account.shopping_list.clear.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CLEAR_SUCCESS = 'customer.account.shopping_list.clear.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART_NOT_FOUND = 'customer.account.shopping_list.items.added_to_cart.not_found';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART_FAILED = 'customer.account.shopping_list.items.added_to_cart.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART = 'customer.account.shopping_list.items.added_to_cart';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_SHARE_SHARE_SHOPPING_LIST_SUCCESSFUL = 'customer.account.shopping_list.share.share_shopping_list_successful';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $viewData = $this->executeIndexAction($request);

        return $this->view($viewData, [], '@ShoppingListPage/views/shopping-list-overview/shopping-list-overview.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        $shoppingListForm = $this->getFactory()
            ->getShoppingListForm()
            ->handleRequest($request);
        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();

        if ($shoppingListForm->isSubmitted() && $shoppingListForm->isValid()) {
            $shoppingListResponseTransfer = $this->getFactory()
                ->getShoppingListClient()
                ->createShoppingList($this->getShoppingListTransfer($shoppingListForm));

            if ($shoppingListResponseTransfer->getIsSuccess()) {
                $shoppingListForm = $this->getFactory()
                    ->getShoppingListForm();
            }

            $this->handleResponseErrors($shoppingListResponseTransfer);
        }

        return [
            'shoppingListCollection' => $this->getCustomerShoppingListCollection(),
            'shoppingListForm' => $shoppingListForm->createView(),
            'shoppingListResponse' => $shoppingListResponseTransfer,
        ];
    }

    /**
     * @param int $idShoppingList
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(int $idShoppingList, Request $request)
    {
        $response = $this->executeUpdateAction($idShoppingList, $request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@ShoppingListPage/views/shopping-list-overview-update/shopping-list-overview-update.twig');
    }

    /**
     * @param int $idShoppingList
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeUpdateAction(int $idShoppingList, Request $request)
    {
        $shoppingListFormDataProvider = $this->getFactory()->createShoppingListFormDataProvider();
        $shoppingListTransfer = $shoppingListFormDataProvider->getData($idShoppingList);
        $shoppingListForm = $this->getFactory()
            ->getShoppingListUpdateForm($shoppingListTransfer)
            ->handleRequest($request);

        if ($shoppingListForm->isSubmitted() && $shoppingListForm->isValid()) {
            $shoppingListResponseTransfer = $this->getFactory()
                ->getShoppingListClient()
                ->updateShoppingList($shoppingListTransfer);

            if ($shoppingListResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_UPDATED);

                return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_UPDATE, [
                    static::ROUTE_PARAM_ID_SHOPPING_LIST => $idShoppingList,
                ]);
            }

            $this->handleResponseErrors($shoppingListResponseTransfer);
        }

        $shoppingListItemProducts = $this->getShoppingListItemProducts($shoppingListTransfer);

        return [
            'shoppingList' => $shoppingListTransfer,
            'shoppingListForm' => $shoppingListForm->createView(),
            'shoppingListItemProducts' => $shoppingListItemProducts,
        ];
    }

    /**
     * @param int $idShoppingList
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(int $idShoppingList): RedirectResponse
    {
        $shoppingListTransfer = new ShoppingListTransfer();
        $shoppingListTransfer
            ->setIdShoppingList($idShoppingList)
            ->setIdCompanyUser($this->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser());

        $shoppingListResponseTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->removeShoppingList($shoppingListTransfer);

        if (!$shoppingListResponseTransfer->getIsSuccess()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED);

            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_SUCCESS);

        return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
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

        $shoppingListTransfer = (new ShoppingListTransfer)
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addShoppingListToCartAction(Request $request): RedirectResponse
    {
        $shoppingListItems = $this->getFactory()
            ->getShoppingListClient()
            ->getShoppingListItemCollection($this->getShoppingListCollectionTransfer($request));

        if (count($shoppingListItems->getItems()) === 0) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART_NOT_FOUND);

            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }

        $result = $this->getFactory()
            ->createAddToCartHandler()
            ->addAllAvailableToCart($shoppingListItems->getItems()->getArrayCopy());

        if ($result->getRequests()->count()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART_FAILED);

            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART);

        return $this->redirectResponseInternal(ShoppingListPageConfig::CART_REDIRECT_URL);
    }

    /**
     * @param int $idShoppingList
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function shareShoppingListAction(int $idShoppingList, Request $request)
    {
        $response = $this->executeShareShoppingListAction($idShoppingList, $request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@ShoppingListPage/views/share-shopping-list/share-shopping-list.twig');
    }

    /**
     * @param int $idShoppingList
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeShareShoppingListAction(int $idShoppingList, Request $request)
    {
        $shareShoppingListForm = $this->getFactory()
            ->getShareShoppingListForm((new ShoppingListTransfer())->setIdShoppingList($idShoppingList))
            ->handleRequest($request);

        if ($shareShoppingListForm->isSubmitted() && $shareShoppingListForm->isValid()) {
            $shoppingListShareResponseTransfer = $this->getFactory()
                ->getShoppingListClient()
                ->updateShareShoppingList($shareShoppingListForm->getData());

            if ($shoppingListShareResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_SHARE_SHARE_SHOPPING_LIST_SUCCESSFUL);

                return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
            }

            $this->addErrorMessage($shoppingListShareResponseTransfer->getError());
        }

        return [
            'idShoppingList' => $idShoppingList,
            'shoppingList' => $shareShoppingListForm->getData(),
            'shareShoppingListForm' => $shareShoppingListForm->createView(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getShoppingListItemProducts(ShoppingListTransfer $shoppingListTransfer): array
    {
        $shoppingListItemProducts = [];
        if ($shoppingListTransfer->getItems()) {
            foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
                $shoppingListItemProducts[$shoppingListItemTransfer->getIdShoppingListItem()] = $this->createProductView($shoppingListItemTransfer);
            }
        }

        return $shoppingListItemProducts;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $shoppingListForm
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function getShoppingListTransfer(FormInterface $shoppingListForm): ShoppingListTransfer
    {
        $customerTransfer = $this->getCustomer();
        $customerTransfer->requireCompanyUserTransfer();
        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer */
        $shoppingListTransfer = $shoppingListForm->getData();
        $shoppingListTransfer->setCustomerReference($customerTransfer->getCustomerReference());
        $shoppingListTransfer->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        return $shoppingListTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function getShoppingListCollectionTransfer(Request $request): ShoppingListCollectionTransfer
    {
        $shoppingListIds = $request->get(static::PARAM_SHOPPING_LISTS);
        $shoppingListCollectionTransfer = new ShoppingListCollectionTransfer();
        $customerTransfer = $this->getCustomer();

        if ($shoppingListIds) {
            foreach ($shoppingListIds as $idShoppingList) {
                $shoppingList = (new ShoppingListTransfer())
                    ->setIdShoppingList($idShoppingList)
                    ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

                $shoppingListCollectionTransfer->addShoppingList($shoppingList);
            }
        }

        return $shoppingListCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        return $this->getFactory()
            ->getShoppingListClient()
            ->getCustomerShoppingListCollection();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(ShoppingListResponseTransfer $shoppingListResponseTransfer): void
    {
        foreach ($shoppingListResponseTransfer->getErrors() as $error) {
            $this->addErrorMessage($error);
        }
    }

    /**
     * @param int $idShoppingList
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shippingListTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function getShoppingListById(int $idShoppingList, ShoppingListCollectionTransfer $shippingListTransferCollection): ShoppingListTransfer
    {
        foreach ($shippingListTransferCollection->getShoppingLists() as $shoppingListTransfer) {
            if ($idShoppingList === $shoppingListTransfer->getIdShoppingList()) {
                return $shoppingListTransfer;
            }
        }

        return new ShoppingListTransfer();
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
