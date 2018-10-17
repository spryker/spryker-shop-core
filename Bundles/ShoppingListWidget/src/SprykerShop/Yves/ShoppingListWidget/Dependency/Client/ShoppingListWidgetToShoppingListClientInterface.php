<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Dependency\Client;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

interface ShoppingListWidgetToShoppingListClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer, array $params = []): ShoppingListItemTransfer;
}
