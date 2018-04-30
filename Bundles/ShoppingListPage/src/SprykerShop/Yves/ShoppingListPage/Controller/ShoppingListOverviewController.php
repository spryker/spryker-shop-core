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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
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
                $this->addSuccessMessage('customer.account.shopping_list.create.success');

                return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
            }

            $this->handleResponseErrors($shoppingListResponseTransfer, $shoppingListForm);
        }

        $data = [
            'shoppingListCollection' => $this->getCustomerShoppingListCollection(),
            'shoppingListForm' => $shoppingListForm->createView(),
            'shoppingListResponse' => $shoppingListResponseTransfer,
        ];

        return $this->view($data, [], '@ShoppingListPage/views/shopping-list-overview/shopping-list-overview.twig');
    }

    /**
     * @param int $idShoppingList
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(int $idShoppingList, Request $request)
    {
        $shoppingListFormDataProvider = $this->getFactory()->createShoppingListFormDataProvider();
        $shoppingListForm = $this->getFactory()
            ->getShoppingListUpdateForm(
                $shoppingListFormDataProvider->getData($idShoppingList)
            )
            ->handleRequest($request);

        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();

        if ($shoppingListForm->isSubmitted() && $shoppingListForm->isValid()) {
            $shoppingListResponseTransfer = $this->getFactory()
                ->getShoppingListClient()
                ->updateShoppingList($shoppingListForm->getData());

            if ($shoppingListResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage('customer.account.shopping_list.updated');

                return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
            }
        }

        $shoppingListCollection = $this->getCustomerShoppingListCollection();

        $data = [
            'shoppingListCollection' => $shoppingListCollection,
            'shoppingListForm' => $shoppingListForm->createView(),
            'idShoppingList' => $shoppingListForm->getData()->getIdShoppingList(),
            'shoppingListResponse' => $shoppingListResponseTransfer,
        ];

        return $this->view($data, [], '@ShoppingListPage/views/shopping-list-overview-update/shopping-list-overview-update.twig');
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
            $this->addErrorMessage('customer.account.shopping_list.delete.failed');
            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }

        $this->addSuccessMessage('customer.account.shopping_list.delete.success');
        return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
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
        if (count($shoppingListItems->getItems())  === 0) {
            $this->addErrorMessage('customer.account.shopping_list.items.added_to_cart.');
            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }


        $result = $this->getFactory()
            ->createAddToCartHandler()
            ->addAllAvailableToCart($shoppingListItems->getItems()->getArrayCopy());

        if ($result->getRequests()->count()) {
            $this->addErrorMessage('customer.account.shopping_list.items.added_to_cart.failed');
            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }

        $this->addSuccessMessage('customer.account.shopping_list.items.added_to_cart');
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
        $shareShoppingListForm = $this->getFactory()
            ->getShareShoppingListForm($idShoppingList)
            ->handleRequest($request);

        if ($shareShoppingListForm->isSubmitted() && $shareShoppingListForm->isValid()) {
            /** @var \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer */
            $shoppingListShareRequestTransfer = $shareShoppingListForm->getData();
            $shoppingListShareResponseTransfer = $this->getFactory()
                ->getShoppingListClient()
                ->shareShoppingList($shoppingListShareRequestTransfer);

            if ($shoppingListShareResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage('customer.account.shopping_list.share.share_shopping_list_successful');
                return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
            }

            $this->addErrorMessage($shoppingListShareResponseTransfer->getError());
        }

        $data = [
            'idShoppingList' => $idShoppingList,
            'shareShoppingListForm' => $shareShoppingListForm->createView(),
            'shoppingListCollection' => $this->getCustomerShoppingListCollection(),
        ];

        return $this->view($data, [], '@ShoppingListPage/views/share-shopping-list/share-shopping-list.twig');
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
     * @param \Symfony\Component\Form\FormInterface $shoppingListForm
     *
     * @return void
     */
    protected function handleResponseErrors(ShoppingListResponseTransfer $shoppingListResponseTransfer, FormInterface $shoppingListForm): void
    {
        foreach ($shoppingListResponseTransfer->getErrors() as $error) {
            $this->addErrorMessage($error);
        }
    }
}
