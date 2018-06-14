<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form\Handler;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Symfony\Component\HttpFoundation\Request;

interface AddToCartFormHandlerInterface
{
    public const PARAM_ID_SHOPPING_LIST_ITEM = 'idShoppingListItem';
    public const PARAM_SHOPPING_LIST_ITEM = 'shoppingListItem';
    public const PARAM_ID_SHOPPING_LIST = 'idShoppingList';
    public const PARAM_ID_ADD_ITEM = 'add-item';
    public const PARAM_ADD_ALL_AVAILABLE = 'add-all-available';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function handleAddToCartRequest(Request $request): ShoppingListItemCollectionTransfer;
}
