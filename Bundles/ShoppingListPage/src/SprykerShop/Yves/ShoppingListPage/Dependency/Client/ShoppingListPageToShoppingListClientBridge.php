<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Dependency\Client;

use Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

class ShoppingListPageToShoppingListClientBridge implements ShoppingListPageToShoppingListClientInterface
{
    /**
     * @var \Spryker\Client\ShoppingList\ShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @param \Spryker\Client\ShoppingList\ShoppingListClientInterface $shoppingListClient
     */
    public function __construct($shoppingListClient)
    {
        $this->shoppingListClient = $shoppingListClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function removeShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->shoppingListClient->removeShoppingList($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function clearShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->shoppingListClient->clearShoppingList($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function shareShoppingList(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer
    {
        return $this->shoppingListClient->shareShoppingList($shoppingListShareRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function updateShoppingListSharedEntities(ShoppingListTransfer $shoppingListTransfer): ShoppingListShareResponseTransfer
    {
        return $this->shoppingListClient->updateShoppingListSharedEntities($shoppingListTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    public function getShoppingListPermissionGroup(): ShoppingListPermissionGroupTransfer
    {
        return $this->shoppingListClient->getShoppingListPermissionGroup();
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer
     */
    public function getShoppingListPermissionGroups(): ShoppingListPermissionGroupCollectionTransfer
    {
        return $this->shoppingListClient->getShoppingListPermissionGroups();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function getShoppingListItemCollectionTransfer(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        return $this->shoppingListClient->getShoppingListItemCollectionTransfer($shoppingListItemCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function getShoppingListItemCollection(ShoppingListCollectionTransfer $shoppingListCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        return $this->shoppingListClient->getShoppingListItemCollection($shoppingListCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function createShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->shoppingListClient->createShoppingList($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function updateShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->shoppingListClient->updateShoppingList($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function removeItemById(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        return $this->shoppingListClient->removeItemById($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        return $this->shoppingListClient->getShoppingList($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function getShoppingListOverviewWithoutProductDetails(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer
    {
        return $this->shoppingListClient->getShoppingListOverviewWithoutProductDetails($shoppingListOverviewRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function getShoppingListOverview(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer
    {
        return $this->shoppingListClient->getShoppingListOverview($shoppingListOverviewRequestTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        return $this->shoppingListClient->getCustomerShoppingListCollection();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    public function addItemCollectionToCart(ShoppingListAddToCartRequestCollectionTransfer $shoppingListAddToCartRequestCollectionTransfer): ShoppingListAddToCartRequestCollectionTransfer
    {
        return $this->shoppingListClient->addItemCollectionToCart($shoppingListAddToCartRequestCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createShoppingListFromQuote(ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer): ShoppingListTransfer
    {
        return $this->shoppingListClient->createShoppingListFromQuote($shoppingListFromCartRequestTransfer);
    }
}
