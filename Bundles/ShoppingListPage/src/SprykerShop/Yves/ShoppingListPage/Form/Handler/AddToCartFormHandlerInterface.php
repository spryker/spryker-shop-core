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
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function handleAddToCartRequest(Request $request): ShoppingListItemCollectionTransfer;
}
