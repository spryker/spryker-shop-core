<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Business;

use Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListAddToCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface;

class AddToCartHandler implements AddToCartHandlerInterface
{
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
     * @param array<\Generated\Shared\Transfer\ShoppingListItemTransfer> $shoppingListItems
     * @param array<int> $itemQuantity
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    public function addAllAvailableToCart(array $shoppingListItems, array $itemQuantity = []): ShoppingListAddToCartRequestCollectionTransfer
    {
        $shoppingListMoveToCartRequestCollectionTransfer = $this->createMoveAvailableItemsToCartRequestCollection(
            $shoppingListItems,
        );

        if ($shoppingListMoveToCartRequestCollectionTransfer->getRequests()->count() === 0) {
            return $shoppingListMoveToCartRequestCollectionTransfer;
        }

        if (count($itemQuantity)) {
            $shoppingListMoveToCartRequestCollectionTransfer = $this->addCustomQuantity($shoppingListMoveToCartRequestCollectionTransfer, $itemQuantity);
        }

        return $this->shoppingListClient->addItemCollectionToCart($shoppingListMoveToCartRequestCollectionTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShoppingListItemTransfer> $shoppingListItems
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    protected function createMoveAvailableItemsToCartRequestCollection(array $shoppingListItems): ShoppingListAddToCartRequestCollectionTransfer
    {
        $shoppingListMoveToCartRequestCollectionTransfer = new ShoppingListAddToCartRequestCollectionTransfer();

        foreach ($shoppingListItems as $shoppingListItemTransfer) {
            $shoppingListMoveToCartRequestTransfer = $this->createShoppingListMoveToCartRequestTransfer(
                $shoppingListItemTransfer,
            );

            $shoppingListMoveToCartRequestCollectionTransfer->addRequest($shoppingListMoveToCartRequestTransfer);
        }

        return $shoppingListMoveToCartRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestTransfer
     */
    protected function createShoppingListMoveToCartRequestTransfer(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListAddToCartRequestTransfer
    {
        return (new ShoppingListAddToCartRequestTransfer())
            ->setSku($shoppingListItemTransfer->getSku())
            ->setQuantity($shoppingListItemTransfer->getQuantity())
            ->setShoppingListItem($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer $shoppingListMoveToCartRequestCollectionTransfer
     * @param array<int> $itemQuantity
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    protected function addCustomQuantity(
        ShoppingListAddToCartRequestCollectionTransfer $shoppingListMoveToCartRequestCollectionTransfer,
        array $itemQuantity
    ): ShoppingListAddToCartRequestCollectionTransfer {
        foreach ($shoppingListMoveToCartRequestCollectionTransfer->getRequests() as $addToCartRequestTransfer) {
            $idShoppingListItem = $addToCartRequestTransfer->getShoppingListItem()->getIdShoppingListItem();

            if (!empty($itemQuantity[$idShoppingListItem])) {
                $addToCartRequestTransfer->setQuantity($itemQuantity[$idShoppingListItem]);
            }
        }

        return $shoppingListMoveToCartRequestCollectionTransfer;
    }
}
