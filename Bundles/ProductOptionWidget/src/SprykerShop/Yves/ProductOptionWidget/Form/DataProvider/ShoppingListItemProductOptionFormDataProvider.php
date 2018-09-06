<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorageTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use SprykerShop\Yves\ProductOptionWidget\Dependency\Client\ProductOptionWidgetToProductOptionStorageClientInterface;
use Symfony\Component\HttpFoundation\Request;

class ShoppingListItemProductOptionFormDataProvider implements ShoppingListItemProductOptionFormDataProviderInterface
{
    protected const FORM_NAME = 'shopping_list_update_form';
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function expandData(ShoppingListTransfer $shoppingListTransfer, Request $request): ShoppingListTransfer
    {
        if (!$request->request->has(static::FORM_NAME)) {
            return $shoppingListTransfer;
        }

        $requestFormData = $request->request->get(static::FORM_NAME);

        foreach ($shoppingListTransfer->getItems() as $key => $itemTransfer) {
            if ($requestFormData[ShoppingListTransfer::ITEMS] && $requestFormData[ShoppingListTransfer::ITEMS][$key]) {
                $idsProductOptionValue = array_filter($requestFormData[ShoppingListTransfer::ITEMS][$key][static::PRODUCT_OPTIONS_FIELD_NAME]);
                $productOptionTransfers = [];
                foreach ($idsProductOptionValue as $idProductOptionValue) {
                    $productOptionTransfers[] = (new ProductOptionTransfer())->setIdProductOptionValue($idProductOptionValue);
                }
                $itemTransfer->setProductOptions(new ArrayObject($productOptionTransfers));
            }
        }

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionGroupStorageTransfer[]|null
     */
    public function getProductOptionGroups(ShoppingListItemTransfer $shoppingListItemTransfer)
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
    protected function getStorageProductOptionGroupCollectionTransfer(ShoppingListItemTransfer $shoppingListItemTransfer)
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
        $storageProductOptionGroupCollectionTransfer = $this->hydrateProductOptionValuesIsSelected($storageProductOptionGroupCollectionTransfer, $shoppingListItemTransfer);

        return $storageProductOptionGroupCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $storageProductOptionGroupCollectionTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    protected function hydrateProductOptionValuesIsSelected(
        ProductAbstractOptionStorageTransfer $storageProductOptionGroupCollectionTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ProductAbstractOptionStorageTransfer {
        $selectedProductOptionIds = $this->getSelectedProductOptionIds($shoppingListItemTransfer);

        foreach ($storageProductOptionGroupCollectionTransfer->getProductOptionGroups() as $productOptionGroup) {
            foreach ($productOptionGroup->getProductOptionValues() as $productOptionValue) {
                $this->hydrateProductOptionValueIsSelected($productOptionValue, $selectedProductOptionIds);
            }
        }

        return $storageProductOptionGroupCollectionTransfer;
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
