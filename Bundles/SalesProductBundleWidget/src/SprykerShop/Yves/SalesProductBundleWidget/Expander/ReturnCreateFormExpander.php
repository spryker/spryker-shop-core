<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductBundleWidget\Expander;

use Generated\Shared\Transfer\ReturnItemTransfer;
use SprykerShop\Yves\SalesProductBundleWidget\Form\ReturnProductBundleForm;
use SprykerShop\Yves\SalesProductBundleWidget\Form\ReturnProductBundleItemsForm;

class ReturnCreateFormExpander implements ReturnCreateFormExpanderInterface
{
    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm::FIELD_RETURN_ITEMS
     * @var string
     */
    protected const FIELD_RETURN_ITEMS = 'returnItems';

    /**
     * @param array $formData
     *
     * @return array
     */
    public function expandFormData(array $formData): array
    {
        if (!isset($formData[static::FIELD_RETURN_ITEMS])) {
            return $formData;
        }

        $itemTransfers = $this->extractItemsFromFormData($formData);

        return [
            static::FIELD_RETURN_ITEMS => $this->getFormFieldsWithoutBundles($itemTransfers),
            ReturnProductBundleForm::FIELD_PRODUCT_BUNDLES => $this->getProductBundleFormFields($itemTransfers),
            ReturnProductBundleItemsForm::KEY_PRODUCT_BUNDLE_ITEMS => $this->getProductBundleItems($itemTransfers),
        ];
    }

    /**
     * @param array $formData
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function extractItemsFromFormData(array $formData): array
    {
        $itemTransfers = [];

        foreach ($formData[static::FIELD_RETURN_ITEMS] as $returnItemData) {
            $itemTransfers[] = $returnItemData[ReturnItemTransfer::ORDER_ITEM];
        }

        return $itemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer[]>
     */
    protected function getFormFieldsWithoutBundles(array $itemTransfers): array
    {
        $itemFormFields = [];

        foreach ($itemTransfers as $itemTransfer) {
            if (!$itemTransfer->getRelatedBundleItemIdentifier()) {
                $itemFormFields[] = [ReturnItemTransfer::ORDER_ITEM => $itemTransfer];
            }
        }

        return $itemFormFields;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer[]>
     */
    protected function getProductBundleFormFields(array $itemTransfers): array
    {
        $productBundleFormFields = [];

        foreach ($itemTransfers as $itemTransfer) {
            $relatedBundleItemIdentifier = $itemTransfer->getRelatedBundleItemIdentifier();

            if ($relatedBundleItemIdentifier && !isset($productBundleFormFields[$relatedBundleItemIdentifier])) {
                $productBundleFormFields[$relatedBundleItemIdentifier] = [
                    ReturnProductBundleItemsForm::KEY_PRODUCT_BUNDLE_DATA => $itemTransfer->getProductBundle(),
                ];
            }
        }

        return $productBundleFormFields;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getProductBundleItems(array $itemTransfers): array
    {
        $productBundleItems = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                $productBundleItems[] = $itemTransfer;
            }
        }

        return $productBundleItems;
    }
}
