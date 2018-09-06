<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Dependency\Client;

use Generated\Shared\Transfer\ShoppingListAddItemsRequestTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;

class ShoppingListWidgetToShoppingListClientBridge implements ShoppingListWidgetToShoppingListClientInterface
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
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        return $this->shoppingListClient->getCustomerShoppingListCollection();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->shoppingListClient->addItem($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListAddItemsRequestTransfer $shoppingListAddItemsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function addItems(ShoppingListAddItemsRequestTransfer $shoppingListAddItemsRequestTransfer): ShoppingListResponseTransfer
    {
        return $this->shoppingListClient->addItems($shoppingListAddItemsRequestTransfer);
    }
}
