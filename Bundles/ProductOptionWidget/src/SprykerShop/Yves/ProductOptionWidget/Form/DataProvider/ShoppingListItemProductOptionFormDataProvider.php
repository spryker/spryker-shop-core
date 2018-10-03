<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\ProductOptionGroupStorageTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorageTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientInterface;

class ShoppingListItemProductOptionFormDataProvider implements ShoppingListItemProductOptionFormDataProviderInterface
{
    protected const SHOPPING_LIST_UPDATE_FORM_NAME = 'shopping_list_update_form';
    protected const PRODUCT_OPTIONS_FIELD_NAME = 'productOptions';

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
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function expandData(ShoppingListTransfer $shoppingListTransfer, array $params): ShoppingListTransfer
    {
        if (!isset($params[static::SHOPPING_LIST_UPDATE_FORM_NAME])) {
            return $shoppingListTransfer;
        }

        $requestFormData = $params[static::SHOPPING_LIST_UPDATE_FORM_NAME];
        $shoppingListTransfer = $this->populateProductOptions($shoppingListTransfer, $requestFormData);

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionGroupStorageTransfer[]|null
     */
    public function getProductOptionGroups(ShoppingListItemTransfer $shoppingListItemTransfer): ArrayObject
    {
        $storageProductOptionGroupCollectionTransfer = $this->getStorageProductOptionGroupCollectionTransfer($shoppingListItemTransfer);

        if (!$storageProductOptionGroupCollectionTransfer) {
            return new ArrayObject();
        }

        $storageProductOptionGroupCollectionTransfer = $this->hydrateStorageProductOptionGroupCollectionTransfer($storageProductOptionGroupCollectionTransfer, $shoppingListItemTransfer);

        return $storageProductOptionGroupCollectionTransfer->getProductOptionGroups();
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
            $productOptionValueIds = $this->getProductOptionValueIds($requestFormData, $itemKey);
            $shoppingListItems[] = $this->populateProductOptionsPerShoppingListItem($shoppingListItemTransfer, $productOptionValueIds);
        }

        return $shoppingListTransfer->setItems(new ArrayObject($shoppingListItems));
    }

    /**
     * @param array $requestFormData
     * @param string $itemKey
     *
     * @return int[]
     */
    protected function getProductOptionValueIds(array $requestFormData, string $itemKey): array
    {
        return array_filter($requestFormData[ShoppingListTransfer::ITEMS][$itemKey][static::PRODUCT_OPTIONS_FIELD_NAME]);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param int[] $productOptionValueIds
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function populateProductOptionsPerShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer, array $productOptionValueIds): ShoppingListItemTransfer
    {
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
