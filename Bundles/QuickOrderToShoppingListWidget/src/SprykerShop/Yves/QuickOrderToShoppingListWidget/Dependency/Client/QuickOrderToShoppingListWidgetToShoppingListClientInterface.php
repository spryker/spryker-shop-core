<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderToShoppingListWidget\Dependency\Client;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

interface QuickOrderToShoppingListWidgetToShoppingListClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer;
}
