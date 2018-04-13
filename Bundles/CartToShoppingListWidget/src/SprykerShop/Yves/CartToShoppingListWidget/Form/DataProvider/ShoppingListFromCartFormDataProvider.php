<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartToShoppingListWidget\Form\DataProvider;

use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use SprykerShop\Yves\CartToShoppingListWidget\Plugin\Provider\CartToShoppingListWidgetControllerProvider;

class ShoppingListFromCartFormDataProvider
{
    /**
     * @param int|null $idQuote
     *
     * @return \Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer
     */
    public function getData(?int $idQuote): ShoppingListFromCartRequestTransfer
    {
        return (new ShoppingListFromCartRequestTransfer)->setIdQuote($idQuote)->setShoppingListName(null);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => ShoppingListFromCartRequestTransfer::class,
            'action' => CartToShoppingListWidgetControllerProvider::ROUTE_CART_TO_SHOPPING_LIST,
        ];
    }
}
