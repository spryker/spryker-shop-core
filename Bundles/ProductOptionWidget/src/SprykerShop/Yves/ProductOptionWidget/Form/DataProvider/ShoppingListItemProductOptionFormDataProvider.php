<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\ProductOptionGroupStorageTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorageTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientInterface;

class ShoppingListItemProductOptionFormDataProvider implements ShoppingListItemProductOptionFormDataProviderInterface
{
    /**
     * @var \SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientInterface
     */
    protected $productOptionStorageClient;

    /**
     * @param \SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientInterface $productOptionStorageClient
     */
    public function __construct(ProductOptionWidgetToProductOptionStorageClientInterface $productOptionStorageClient)
    {
        $this->productOptionStorageClient = $productOptionStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionGroupStorageTransfer[]|null
     */
    public function findProductOptionGroupsByShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ArrayObject
    {
        $storageProductOptionGroupCollectionTransfer = $this->getStorageProductOptionGroupCollectionTransfer($shoppingListItemTransfer);

        if (!$storageProductOptionGroupCollectionTransfer) {
            return new ArrayObject();
        }

        $storageProductOptionGroupCollectionTransfer = $this->hydrateStorageProductOptionGroupCollectionTransfer($storageProductOptionGroupCollectionTransfer, $shoppingListItemTransfer);

        return $storageProductOptionGroupCollectionTransfer->getProductOptionGroups();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    protected function getStorageProductOptionGroupCollectionTransfer(ShoppingListItemTransfer $shoppingListItemTransfer): ?ProductAbstractOptionStorageTransfer
    {
        return $this->productOptionStorageClient
            ->getProductOptionsForCurrentStore($shoppingListItemTransfer->getIdProductAbstract());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $storageProductOptionGroupCollectionTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    protected function hydrateStorageProductOptionGroupCollectionTransfer(
        ProductAbstractOptionStorageTransfer $storageProductOptionGroupCollectionTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ProductAbstractOptionStorageTransfer {
        $storageProductOptionGroupCollectionTransfer = $this->hydrateProductOptionValues($storageProductOptionGroupCollectionTransfer, $shoppingListItemTransfer);

        return $storageProductOptionGroupCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $storageProductOptionGroupCollectionTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    protected function hydrateProductOptionValues(
        ProductAbstractOptionStorageTransfer $storageProductOptionGroupCollectionTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ProductAbstractOptionStorageTransfer {
        $selectedProductOptionIds = $this->getSelectedProductOptionIds($shoppingListItemTransfer);

        $productOptionGroups = new ArrayObject();
        foreach ($storageProductOptionGroupCollectionTransfer->getProductOptionGroups() as $productOptionGroup) {
            $productOptionGroup = $this->hydrateProductOptionValuesPerOptionGroup($productOptionGroup, $selectedProductOptionIds);
            $productOptionGroups->append($productOptionGroup);
        }

        return $storageProductOptionGroupCollectionTransfer->setProductOptionGroups($productOptionGroups);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupStorageTransfer $productOptionGroup
     * @param int[] $selectedProductOptionIds
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupStorageTransfer
     */
    protected function hydrateProductOptionValuesPerOptionGroup(
        ProductOptionGroupStorageTransfer $productOptionGroup,
        array $selectedProductOptionIds
    ): ProductOptionGroupStorageTransfer {
        $productOptionValues = new ArrayObject();
        foreach ($productOptionGroup->getProductOptionValues() as $productOptionValue) {
            $productOptionValues->append($this->hydrateProductOptionValueIsSelected($productOptionValue, $selectedProductOptionIds));
        }
        $productOptionGroup->setProductOptionValues($productOptionValues);

        return $productOptionGroup;
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
     * @param \Generated\Shared\Transfer\ProductOptionValueStorageTransfer $productOptionValue
     * @param int[] $selectedProductOptionIds
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorageTransfer
     */
    protected function hydrateProductOptionValueIsSelected(
        ProductOptionValueStorageTransfer $productOptionValue,
        array $selectedProductOptionIds
    ): ProductOptionValueStorageTransfer {
        if (in_array($productOptionValue->getIdProductOptionValue(), $selectedProductOptionIds)) {
            $productOptionValue->setIsSelected(true);
        }

        return $productOptionValue;
    }
}
