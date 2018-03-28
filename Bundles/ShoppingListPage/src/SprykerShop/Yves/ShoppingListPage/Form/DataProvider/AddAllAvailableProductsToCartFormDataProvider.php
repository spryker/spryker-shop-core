<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form\DataProvider;

use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use SprykerShop\Yves\ShoppingListPage\Form\AddAllAvailableProductsToCartForm;

class AddAllAvailableProductsToCartFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer|null $shoppingListOverviewResponseTransfer
     *
     * @return array
     */
    public function getData(?ShoppingListOverviewResponseTransfer $shoppingListOverviewResponseTransfer): array
    {
        $data = [
            AddAllAvailableProductsToCartForm::SHOPPING_LIST_ITEM_COLLECTION => $this->getShoppingListItemCollection($shoppingListOverviewResponseTransfer),
        ];

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer|null $shoppingListOverviewResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer[]
     */
    protected function getShoppingListItemCollection(?ShoppingListOverviewResponseTransfer $shoppingListOverviewResponseTransfer): array
    {
        if (!$shoppingListOverviewResponseTransfer) {
            return [];
        }

        return $shoppingListOverviewResponseTransfer->getItemsCollection()
            ->getItems()
            ->getArrayCopy();
    }
}
