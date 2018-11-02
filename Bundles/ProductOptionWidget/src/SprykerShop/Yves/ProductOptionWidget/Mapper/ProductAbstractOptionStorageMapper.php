<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\ProductOptionGroupStorageTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorageTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;

class ProductAbstractOptionStorageMapper implements ProductAbstractOptionStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    public function mapShoppingListItemProductOptionsToProductAbstractOptionStorage(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ProductAbstractOptionStorageTransfer {
        $selectedProductOptionIds = $this->getSelectedProductOptionIds($shoppingListItemTransfer);

        $productOptionGroups = new ArrayObject();
        foreach ($productAbstractOptionStorageTransfer->getProductOptionGroups() as $productOptionGroup) {
            $productOptionGroup = $this->mapArrayToProductOptionGroupStorage($productOptionGroup, $selectedProductOptionIds);
            $productOptionGroups->append($productOptionGroup);
        }

        return $productAbstractOptionStorageTransfer->setProductOptionGroups($productOptionGroups);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return int[]
     */
    protected function getSelectedProductOptionIds(ShoppingListItemTransfer $shoppingListItemTransfer): array
    {
        $selectedProductOptionIds = [];
        foreach ($shoppingListItemTransfer->getProductOptions() as $productOptionTransfer) {
            $selectedProductOptionIds[] = $productOptionTransfer->getIdProductOptionValue();
        }

        return $selectedProductOptionIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupStorageTransfer $productOptionGroup
     * @param int[] $selectedProductOptionIds
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupStorageTransfer
     */
    protected function mapArrayToProductOptionGroupStorage(
        ProductOptionGroupStorageTransfer $productOptionGroup,
        array $selectedProductOptionIds
    ): ProductOptionGroupStorageTransfer {
        $productOptionValues = new ArrayObject();
        foreach ($productOptionGroup->getProductOptionValues() as $productOptionValue) {
            $productOptionValues->append($this->mapArrayToProductOptionValueStorage($productOptionValue, $selectedProductOptionIds));
        }
        $productOptionGroup->setProductOptionValues($productOptionValues);

        return $productOptionGroup;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueStorageTransfer $productOptionValue
     * @param int[] $selectedProductOptionIds
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorageTransfer
     */
    protected function mapArrayToProductOptionValueStorage(
        ProductOptionValueStorageTransfer $productOptionValue,
        array $selectedProductOptionIds
    ): ProductOptionValueStorageTransfer {
        if (in_array($productOptionValue->getIdProductOptionValue(), $selectedProductOptionIds)) {
            $productOptionValue->setIsSelected(true);
        }

        return $productOptionValue;
    }
}
