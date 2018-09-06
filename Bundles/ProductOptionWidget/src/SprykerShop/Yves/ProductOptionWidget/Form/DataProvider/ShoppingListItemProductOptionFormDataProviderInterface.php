<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Form\DataProvider;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Symfony\Component\HttpFoundation\Request;

interface ShoppingListItemProductOptionFormDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function expandData(ShoppingListTransfer $shoppingListTransfer, Request $request): ShoppingListTransfer;

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionGroupStorageTransfer[]|null
     */
    public function getProductOptionGroups(ShoppingListItemTransfer $shoppingListItemTransfer);
}
