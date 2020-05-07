<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Expander;

use SprykerShop\Yves\ProductBundleWidget\Form\ProductBundleItemsForm;

class SalesReturnPageFormExpander implements SalesReturnPageFormExpanderInterface
{
    protected const PARAM_RETURN_ITEMS = 'returnItems';

    /**
     * @param array $formData
     *
     * @return array
     */
    public function expandFormData(array $formData): array
    {
        if (!isset($formData[static::PARAM_RETURN_ITEMS])) {
            return $formData;
        }

        $productBundleItems = [];
        $expandedFormData = [
            static::PARAM_RETURN_ITEMS => [],
            ProductBundleItemsForm::FIELD_PRODUCT_BUNDLES => [],
        ];

        foreach ($formData[static::PARAM_RETURN_ITEMS] as $returnItemData) {
            if (!$returnItemData[ProductBundleItemsForm::PARAM_ORDER_ITEM]) {
                continue;
            }

            $itemTransfer = $returnItemData[ProductBundleItemsForm::PARAM_ORDER_ITEM];

            if ($itemTransfer->getProductBundle()) {
                $productBundleSku = $itemTransfer->getProductBundle()->getSku();

                if (!isset($productBundleItems[$productBundleSku])) {
                    $productBundleItems[$productBundleSku] = [ProductBundleItemsForm::PARAM_PRODUCT_BUNDLE_DATA => $itemTransfer->getProductBundle()];
                }

                $productBundleItems[$productBundleSku][ProductBundleItemsForm::PARAM_PRODUCT_BUNDLE_ITEMS][] = [ProductBundleItemsForm::PARAM_ORDER_ITEM => $itemTransfer];

                continue;
            }

            $expandedFormData[static::PARAM_RETURN_ITEMS][] = [ProductBundleItemsForm::PARAM_ORDER_ITEM => $itemTransfer];
        }

        $expandedFormData[ProductBundleItemsForm::FIELD_PRODUCT_BUNDLES] = $productBundleItems;

        return $expandedFormData;
    }
}
