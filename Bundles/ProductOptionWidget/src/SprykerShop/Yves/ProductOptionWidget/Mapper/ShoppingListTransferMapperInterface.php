<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Mapper;

use Generated\Shared\Transfer\ShoppingListTransfer;

interface ShoppingListTransferMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function mapProductOptionsToShoppingList(ShoppingListTransfer $shoppingListTransfer, array $params): ShoppingListTransfer;
}
