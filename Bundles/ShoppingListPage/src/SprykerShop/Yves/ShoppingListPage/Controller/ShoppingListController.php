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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShoppingListController extends AbstractShoppingListController
{
    protected const PARAM_SKU = 'sku';
    protected const PARAM_QUANTITY = 'quantity';
    protected const PARAM_ID_SHOPPING_LIST_ITEM = 'idShoppingListItem';
    protected const PARAM_SHOPPING_LIST_ITEM = 'shoppingListItem';
    protected const PARAM_ID_SHOPPING_LIST = 'idShoppingList';
    protected const PARAM_REDIRECT_ROUTE_PARAMETERS = 'redirect-route-parameters';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_REMOVE_FAILED = 'customer.account.shopping_list.item.remove.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_REMOVE_SUCCESS = 'customer.account.shopping_list.item.remove.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADDED_TO_CART_FAILED = 'customer.account.shopping_list.item.added_to_cart.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADDED_TO_CART = 'customer.account.shopping_list.item.added_to_cart';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADDED_ALL_AVAILABLE_TO_CART_FAILED = 'customer.account.shopping_list.item.added_all_available_to_cart.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADDED_ALL_AVAILABLE_TO_CART = 'customer.account.shopping_list.item.added_all_available_to_cart';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_SELECT_ITEM = 'customer.account.shopping_list.item.select_item';
    protected const GLOSSARY_KEY_SHOPPING_LIST_NOT_FOUND = 'shopping_list.not_found';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_NOT_ADDED = 'customer.account.shopping_list.item.not_added';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ADD_ITEM_SUCCESS = 'customer.account.shopping_list.add_item.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART_SELECT_LIST = 'customer.account.shopping_list.items.added_to_cart.select_list';

    /**
     * @use \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_CART
     */
    protected const ROUTE_CART_PAGE = 'cart';

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
            [],
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
            $errorMessages = $this->getFactory()->getZedRequestClient()->getLastResponseErrorMessages();
            foreach ($errorMessages as $errorMessageTransfer) {
                $this->addErrorMessage($errorMessageTransfer->getValue());
            }

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

        return $this->redirectResponseInternal(static::ROUTE_CART_PAGE);
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
            [],
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function quickAddToShoppingListAction(Request $request, string $sku): RedirectResponse
    {
        $quantity = $request->get('quantity', 1);

        $idShoppingList = $this->getShoppingListIdFromRequest($request);
        if ($idShoppingList === null) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEMS_ADDED_TO_CART_SELECT_LIST);

            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }

        $shoppingListItemTransfer = $this->executeQuickAddToShoppingListAction($sku, $quantity, $idShoppingList, $request);
        if (!$shoppingListItemTransfer->getIdShoppingListItem()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_NOT_ADDED);

            return $this->getQuickAddToShoppingListRedirectResponse($shoppingListItemTransfer);
        }
        $this->addSuccessMessage(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ADD_ITEM_SUCCESS);

        return $this->getQuickAddToShoppingListRedirectResponse($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getQuickAddToShoppingListRedirectResponse(ShoppingListItemTransfer $shoppingListItemTransfer): RedirectResponse
    {
        if (!$shoppingListItemTransfer->getFkShoppingList()) {
            return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST);
        }

        return $this->redirectResponseInternal(ShoppingListPageControllerProvider::ROUTE_SHOPPING_LIST_DETAILS, [
            'idShoppingList' => $shoppingListItemTransfer->getFkShoppingList(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int|null
     */
    protected function getShoppingListIdFromRequest(Request $request): ?int
    {
        $additionalRequestParams = $this->getFactory()->getUtilEncodingService()->decodeJson(
            urldecode(
                $request->get(static::PARAM_REDIRECT_ROUTE_PARAMETERS)
            ),
            true
        );

        if (is_array($additionalRequestParams) && array_key_exists(static::PARAM_ID_SHOPPING_LIST, $additionalRequestParams)) {
            return (int)$additionalRequestParams[static::PARAM_ID_SHOPPING_LIST];
        }

        return null;
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param int $idShoppingList
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function executeQuickAddToShoppingListAction(string $sku, int $quantity, int $idShoppingList, Request $request): ShoppingListItemTransfer
    {
        $customerTransfer = $this->getCustomer();

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setSku($sku)
            ->setQuantity($quantity)
            ->setFkShoppingList($idShoppingList);

        if ($customerTransfer === null || $customerTransfer->getCompanyUserTransfer() === null) {
            return $shoppingListItemTransfer;
        }

        $shoppingListItemTransfer->setCustomerReference($customerTransfer->getCustomerReference())
            ->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        // Does not pass request parameters because they are not validated.
        return $this->getFactory()
            ->getShoppingListClient()
            ->addItem($shoppingListItemTransfer);
    }
}
