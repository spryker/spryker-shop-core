<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use SprykerShop\Yves\ShoppingListPage\Plugin\Provider\ShoppingListPageControllerProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShoppingListOverviewController extends AbstractShoppingListController
{
    protected const PARAM_SHOPPING_LISTS = 'shoppingLists';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_UPDATED = 'customer.account.shopping_list.updated';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED = 'customer.account.shopping_list.delete.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_SUCCESS = 'customer.account.shopping_list.delete.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART_NOT_FOUND = 'customer.account.shopping_list.items.added_to_cart.not_found';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART_FAILED = 'customer.account.shopping_list.items.added_to_cart.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART = 'customer.account.shopping_list.items.added_to_cart';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_SHARE_SHARE_SHOPPING_LIST_SUCCESSFUL = 'customer.account.shopping_list.share.share_shopping_list_successful';
    protected const GLOSSARY_KEY_SHOPPING_LIST_NOT_FOUND = 'shopping_list.not_found';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CLEAR_SUCCESS = 'customer.account.shopping_list.clear.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_OVERVIEW_CREATE_SUCCESSFUL = 'customer.account.shopping_list.overview.create.success';

    /**
     * @use \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_CART
     */
    protected const ROUTE_CART_PAGE = 'cart';

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
                $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_OVERVIEW_CREATE_SUCCESSFUL);

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
        $shoppingListTransfer = $shoppingListFormDataProvider->getData($idShoppingList, $request->request->all());

        if (!$shoppingListTransfer->getIdShoppingList()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_SHOPPING_LIST_NOT_FOUND);

            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }

        $shoppingListForm = $this->getFactory()
            ->getShoppingListUpdateForm(
                $shoppingListTransfer
            )
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

        $productViewTransfers = $this->getProductViewTransfers($shoppingListTransfer);

        return [
            'shoppingList' => $shoppingListTransfer,
            'shoppingListForm' => $shoppingListForm->createView(),
            'productViewTransfers' => $productViewTransfers,
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
            $this->handleResponseErrors($shoppingListResponseTransfer);

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

        return $this->redirectResponseInternal(static::ROUTE_CART_PAGE);
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
                ->updateShoppingListSharedEntities($shareShoppingListForm->getData());

            if ($shoppingListShareResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_SHARE_SHARE_SHOPPING_LIST_SUCCESSFUL);

                return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
            }

            $this->addErrorMessage($shoppingListShareResponseTransfer->getError());
        }

        $shoppingListTransferCollection = $this->getCustomerShoppingListCollection();
        $shoppingListTransfer = $this->getShoppingListById($idShoppingList, $shoppingListTransferCollection);

        if (!$shoppingListTransfer->getIdShoppingList()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_SHOPPING_LIST_NOT_FOUND);

            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
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
    protected function getProductViewTransfers(ShoppingListTransfer $shoppingListTransfer): array
    {
        $productViewTransfers = [];
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $productViewTransfers[$shoppingListItemTransfer->getIdShoppingListItem()] = $this->createProductView($shoppingListItemTransfer);
        }

        return $productViewTransfers;
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
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListTransferCollection
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function getShoppingListById(int $idShoppingList, ShoppingListCollectionTransfer $shoppingListTransferCollection): ShoppingListTransfer
    {
        foreach ($shoppingListTransferCollection->getShoppingLists() as $shoppingListTransfer) {
            if ($idShoppingList === $shoppingListTransfer->getIdShoppingList()) {
                return $shoppingListTransfer;
            }
        }

        return new ShoppingListTransfer();
    }
}
