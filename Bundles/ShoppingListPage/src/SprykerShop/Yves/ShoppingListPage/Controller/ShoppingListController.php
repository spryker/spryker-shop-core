<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Controller;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use SprykerShop\Yves\ShoppingListPage\Plugin\Provider\ShoppingListPageControllerProvider;
use SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 */
class ShoppingListController extends AbstractShoppingListController
{
    protected const PARAM_SKU = 'sku';
    protected const PARAM_QUANTITY = 'quantity';
    protected const PARAM_ID_SHOPPING_LIST_ITEM = 'idShoppingListItem';
    protected const PARAM_SHOPPING_LIST_ITEM = 'shoppingListItem';
    protected const PARAM_ID_SHOPPING_LIST = 'idShoppingList';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_REMOVE_FAILED = 'customer.account.shopping_list.item.remove.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_REMOVE_SUCCESS = 'customer.account.shopping_list.item.remove.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADDED_TO_CART_FAILED = 'customer.account.shopping_list.item.added_to_cart.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADDED_TO_CART = 'customer.account.shopping_list.item.added_to_cart';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADDED_ALL_AVAILABLE_TO_CART_FAILED = 'customer.account.shopping_list.item.added_all_available_to_cart.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADDED_ALL_AVAILABLE_TO_CART = 'customer.account.shopping_list.item.added_all_available_to_cart';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_SELECT_ITEM = 'customer.account.shopping_list.item.select_item';
    protected const GLOSSARY_KEY_SHOPPING_LIST_NOT_FOUND = 'shopping_list.not_found';

    /**
     * @param int $idShoppingList
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(int $idShoppingList)
    {
        $response = $this->executeIndexAction($idShoppingList);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            $this->getFactory()->getShoppingListViewWidgetPlugins(),
            '@ShoppingListPage/views/shopping-list/shopping-list.twig'
        );
    }

    /**
     * @param int $idShoppingList
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(int $idShoppingList)
    {
        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($idShoppingList)
            ->setIdCompanyUser($this->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser());

        $shoppingListOverviewRequest = (new ShoppingListOverviewRequestTransfer())
            ->setShoppingList($shoppingListTransfer);

        $shoppingListOverviewResponseTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->getShoppingListOverviewWithoutProductDetails($shoppingListOverviewRequest);

        if ($shoppingListOverviewResponseTransfer->getIsSuccess() !== true) {
            $this->addErrorMessage(static::GLOSSARY_KEY_SHOPPING_LIST_NOT_FOUND);
            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }

        $shoppingListItems = $this->getShoppingListItems($shoppingListOverviewResponseTransfer);

        return [
            'shoppingListItems' => $shoppingListItems,
            'shoppingListOverview' => $shoppingListOverviewResponseTransfer,
        ];
    }

    /**
     * @param int $idShoppingList
     * @param int $idShoppingListItem
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeItemAction(int $idShoppingList, int $idShoppingListItem): RedirectResponse
    {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($idShoppingListItem)
            ->setFkShoppingList($idShoppingList)
            ->setIdCompanyUser($this->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser());

        $shoppingListItemResponseTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->removeItemById($shoppingListItemTransfer);

        if (!$shoppingListItemResponseTransfer->getIsSuccess()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_REMOVE_FAILED);

            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS, [
                'idShoppingList' => $shoppingListItemTransfer->getFkShoppingList(),
            ]);
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_REMOVE_SUCCESS);

        return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS, [
            'idShoppingList' => $shoppingListItemTransfer->getFkShoppingList(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToCartAction(Request $request): RedirectResponse
    {
        $shoppingListItemTransferCollection = $this->getFactory()->createAddToCartFormHandler()->handleAddToCartRequest($request);
        if (!$shoppingListItemTransferCollection->getItems()->count()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_SELECT_ITEM);
            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS, [
                'idShoppingList' => $request->get(static::PARAM_ID_SHOPPING_LIST),
            ]);
        }

        $shoppingListItemCollectionTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->getShoppingListItemCollectionTransfer($shoppingListItemTransferCollection);

        $quantity = $request->get(static::PARAM_SHOPPING_LIST_ITEM)[static::PARAM_QUANTITY] ?? [];
        $result = $this->getFactory()
            ->createAddToCartHandler()
            ->addAllAvailableToCart($shoppingListItemCollectionTransfer->getItems()->getArrayCopy(), $quantity);

        if ($result->getRequests()->count()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADDED_TO_CART_FAILED);
            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS, [
                'idShoppingList' => $request->get(static::PARAM_ID_SHOPPING_LIST),
            ]);
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADDED_TO_CART);

        return $this->redirectResponseInternal(ShoppingListPageConfig::CART_REDIRECT_URL);
    }

    /**
     * @param int $idShoppingList
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function printShoppingListAction(int $idShoppingList)
    {
        $response = $this->executePrintShoppingListAction($idShoppingList);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            $this->getFactory()->getPrintShoppingListWidgetPlugins(),
            '@ShoppingListPage/views/shopping-list/print-shopping-list.twig'
        );
    }

    /**
     * @param int $idShoppingList
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executePrintShoppingListAction(int $idShoppingList)
    {
        $shoppingListOverviewResponseTransfer = $this->getShoppingListOverviewResponseTransfer($idShoppingList);

        if ($shoppingListOverviewResponseTransfer->getIsSuccess() !== true) {
            $this->addErrorMessage(static::GLOSSARY_KEY_SHOPPING_LIST_NOT_FOUND);

            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }

        $shoppingListItems = $this->getShoppingListItems($shoppingListOverviewResponseTransfer);

        return [
            'shoppingListItems' => $shoppingListItems,
            'shoppingListOverview' => $shoppingListOverviewResponseTransfer,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer $shoppingListOverviewResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getShoppingListItems(ShoppingListOverviewResponseTransfer $shoppingListOverviewResponseTransfer): array
    {
        $shoppingListItems = [];
        if ($shoppingListOverviewResponseTransfer->getItemsCollection()) {
            foreach ($shoppingListOverviewResponseTransfer->getItemsCollection()->getItems() as $item) {
                $shoppingListItems[] = $this->createProductView($item);
            }
        }

        return $shoppingListItems;
    }

    /**
     * @param int $idShoppingList
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    protected function getShoppingListOverviewResponseTransfer(int $idShoppingList): ShoppingListOverviewResponseTransfer
    {
        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($idShoppingList)
            ->setIdCompanyUser($this->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser());

        $shoppingListOverviewRequest = (new ShoppingListOverviewRequestTransfer())
            ->setShoppingList($shoppingListTransfer);

        $shoppingListOverviewResponseTransfer = $this->getFactory()
            ->getShoppingListClient()
            ->getShoppingListOverviewWithoutProductDetails($shoppingListOverviewRequest);

        return $shoppingListOverviewResponseTransfer;
    }
}
