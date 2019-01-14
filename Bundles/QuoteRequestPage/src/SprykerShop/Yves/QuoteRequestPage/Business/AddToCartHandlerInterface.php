<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Business;

use Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer;

interface AddToCartHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer[] $shoppingListItems
     * @param array $itemQuantity
     *
     * @return \Generated\Shared\Transfer\ShoppingListAddToCartRequestCollectionTransfer
     */
    public function addAllAvailableToCart(array $shoppingListItems, array $itemQuantity = []): ShoppingListAddToCartRequestCollectionTransfer;
}
