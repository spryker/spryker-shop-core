<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Dependency\Client;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Spryker\Client\ShoppingListSession\ShoppingListSessionClientInterface;

class ShoppingListWidgetToShoppingListSessionClientBridge implements ShoppingListWidgetToShoppingListSessionClientInterface
{
    /**
     * @var \Spryker\Client\ShoppingListSession\ShoppingListSessionClientInterface
     */
    protected $shoppingListSessionClient;

    /**
     * @param \Spryker\Client\ShoppingListSession\ShoppingListSessionClientInterface $shoppingListSessionClient
     */
    public function __construct(ShoppingListSessionClientInterface $shoppingListSessionClient)
    {
        $this->shoppingListSessionClient = $shoppingListSessionClient;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer
    {
        return $this->shoppingListSessionClient->getCustomerShoppingListCollection();
    }
}
