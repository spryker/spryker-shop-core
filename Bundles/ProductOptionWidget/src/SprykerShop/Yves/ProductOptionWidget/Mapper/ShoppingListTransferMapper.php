<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;

class ShoppingListTransferMapper implements ShoppingListTransferMapperInterface
{
    protected const SHOPPING_LIST_UPDATE_FORM_NAME = 'shopping_list_update_form';
    protected const PRODUCT_OPTIONS_FIELD_NAME = 'productOptions';

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function mapProductOptionsToShoppingList(ShoppingListTransfer $shoppingListTransfer, array $params): ShoppingListTransfer
    {
        if (!isset($params[static::SHOPPING_LIST_UPDATE_FORM_NAME])) {
            return $shoppingListTransfer;
        }

        $requestFormData = $params[static::SHOPPING_LIST_UPDATE_FORM_NAME];
        $shoppingListTransfer = $this->populateProductOptions($shoppingListTransfer, $requestFormData);

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param array $requestFormData
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function populateProductOptions(ShoppingListTransfer $shoppingListTransfer, array $requestFormData): ShoppingListTransfer
    {
        $shoppingListItems = [];

        foreach ($shoppingListTransfer->getItems() as $itemKey => $shoppingListItemTransfer) {
            if (!$requestFormData[ShoppingListTransfer::ITEMS] || !$requestFormData[ShoppingListTransfer::ITEMS][$itemKey]) {
                continue;
            }

            if ($this->hasProductOptions($requestFormData, $itemKey)) {
                $productOptionValueIds = $this->filterProductOptionValueIds($requestFormData, $itemKey);
                $shoppingListItemTransfer = $this->populateProductOptionsPerShoppingListItem($shoppingListItemTransfer, $productOptionValueIds);
            }

            $shoppingListItems[] = $shoppingListItemTransfer;
        }

        return $shoppingListTransfer->setItems(new ArrayObject($shoppingListItems));
    }

    /**
     * @param array $requestFormData
     * @param string $itemKey
     *
     * @return int[]
     */
    protected function filterProductOptionValueIds(array $requestFormData, string $itemKey): array
    {
        return array_filter($requestFormData[ShoppingListTransfer::ITEMS][$itemKey][static::PRODUCT_OPTIONS_FIELD_NAME]);
    }

    /**
     * @param array $requestFormData
     * @param string $itemKey
     *
     * @return bool
     */
    protected function hasProductOptions(array $requestFormData, string $itemKey): bool
    {
        return !empty($requestFormData[ShoppingListTransfer::ITEMS][$itemKey][static::PRODUCT_OPTIONS_FIELD_NAME]);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param int[] $productOptionValueIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function populateProductOptionsPerShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        array $productOptionValueIds
    ): ShoppingListItemTransfer {
        $productOptionTransfers = $this->createProductOptionTransfers($productOptionValueIds);
        $shoppingListItemTransfer->setProductOptions($productOptionTransfers);

        return $shoppingListItemTransfer;
    }

    /**
     * @param int[] $productOptionValueIds
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function createProductOptionTransfers(array $productOptionValueIds): ArrayObject
    {
        $productOptionTransfers = [];

        foreach ($productOptionValueIds as $productOptionValueId) {
            $productOptionTransfers[] = $this->createProductOptionTransfer($productOptionValueId);
        }

        return new ArrayObject($productOptionTransfers);
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function createProductOptionTransfer(int $idProductOptionValue): ProductOptionTransfer
    {
        return (new ProductOptionTransfer())->setIdProductOptionValue($idProductOptionValue);
    }
}
