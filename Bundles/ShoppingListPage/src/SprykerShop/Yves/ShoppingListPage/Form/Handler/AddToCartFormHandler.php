<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form\Handler;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface;
use Symfony\Component\HttpFoundation\Request;

class AddToCartFormHandler implements AddToCartFormHandlerInterface
{
    protected const PARAM_ID_SHOPPING_LIST_ITEM = 'idShoppingListItem';
    protected const PARAM_SHOPPING_LIST_ITEM = 'shoppingListItem';
    protected const PARAM_SHOPPING_LIST_ITEMS = 'shoppingListItems';
    protected const PARAM_ID_SHOPPING_LIST = 'idShoppingList';
    protected const PARAM_ID_ADD_ITEM = 'add-item';
    protected const PARAM_ADD_ALL_AVAILABLE = 'add-all-available';

    /**
     * @var \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @var \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface $shoppingListClient
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface $customerClient
     */
    public function __construct(
        ShoppingListPageToShoppingListClientInterface $shoppingListClient,
        ShoppingListPageToCustomerClientInterface $customerClient
    ) {
        $this->shoppingListClient = $shoppingListClient;
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function handleAddToCartRequest(Request $request): ShoppingListItemCollectionTransfer
    {
        if ($request->get(static::PARAM_ID_ADD_ITEM)) {
            return $this->getShoppingListItemTransferFromRequest($request);
        }

        if ($request->get(static::PARAM_ADD_ALL_AVAILABLE)) {
            return $this->getAllAvailableRequestItems($request);
        }

        return $this->getShoppingListItemCollectionTransferFromRequest($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function getShoppingListItemTransferFromRequest(Request $request): ShoppingListItemCollectionTransfer
    {
        $shoppingListItemTransfer = $this->createShoppingListItemTransfer(
            $request->request->getInt(static::PARAM_ID_ADD_ITEM),
            $request->request->getInt(static::PARAM_ID_SHOPPING_LIST),
            (array)$request->request->get(static::PARAM_SHOPPING_LIST_ITEMS)
        );

        return (new ShoppingListItemCollectionTransfer())->addItem($shoppingListItemTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function getAllAvailableRequestItems(Request $request): ShoppingListItemCollectionTransfer
    {
        $shoppingListItemCollectionTransfer = new ShoppingListItemCollectionTransfer();
        $shoppingListItemInformation = (array)$request->request->get(static::PARAM_SHOPPING_LIST_ITEMS);

        foreach ($shoppingListItemInformation as $shoppingListItem) {
            $shoppingListItemTransfer = new ShoppingListItemTransfer();
            $shoppingListItemTransfer->fromArray(json_decode($shoppingListItem, true));
            $shoppingListItemCollectionTransfer->addItem($shoppingListItemTransfer);
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function getShoppingListItemCollectionTransferFromRequest(Request $request): ShoppingListItemCollectionTransfer
    {
        $shoppingListCollectionTransfer = new ShoppingListItemCollectionTransfer();
        $shoppingListItemRequest = $request->get(static::PARAM_SHOPPING_LIST_ITEM);

        if (!empty($shoppingListItemRequest[static::PARAM_ID_SHOPPING_LIST_ITEM])) {
            $shoppingListItemInformation = (array)$request->request->get(static::PARAM_SHOPPING_LIST_ITEMS);
            $idShoppingList = $request->request->getInt(static::PARAM_ID_SHOPPING_LIST);

            foreach ($shoppingListItemRequest[static::PARAM_ID_SHOPPING_LIST_ITEM] as $idShoppingListItem) {
                $shoppingListItemTransfer = $this->createShoppingListItemTransfer(
                    $idShoppingListItem,
                    $idShoppingList,
                    $shoppingListItemInformation
                );
                $shoppingListCollectionTransfer->addItem($shoppingListItemTransfer);
            }
        }

        return $shoppingListCollectionTransfer;
    }

    /**
     * @param int $idShoppingListItem
     * @param int $idShoppingList
     * @param mixed[] $shoppingListItemInformation
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function createShoppingListItemTransfer(
        int $idShoppingListItem,
        int $idShoppingList,
        array $shoppingListItemInformation
    ): ShoppingListItemTransfer {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($idShoppingListItem)
            ->setFkShoppingList($idShoppingList);

        if (isset($shoppingListItemInformation[$idShoppingListItem])) {
            $shoppingListItem = json_decode($shoppingListItemInformation[$idShoppingListItem], true);
            $shoppingListItemTransfer->fromArray($shoppingListItem, true);
        }

        return $shoppingListItemTransfer;
    }
}
