<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use SprykerShop\Yves\ProductBundleWidget\Form\ProductBundleItemsForm;

class SalesReturnPageFormHandler implements SalesReturnPageFormHandlerInterface
{
    protected const CUSTOM_REASON_VALUE = 'custom_reason';

    /**
     * @param array $returnItemsList
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handleFormData(array $returnItemsList, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer
    {
        $productBundlesList = $returnItemsList[ProductBundleItemsForm::FIELD_PRODUCT_BUNDLES] ?? [];
        $producBundleItemTransfersCollection = $returnItemsList[ProductBundleItemsForm::PARAM_PRODUCT_BUNDLE_ITEMS] ?? [];

        foreach ($productBundlesList as $productBundleData) {
            if (!$productBundleData[ItemTransfer::IS_RETURNABLE] || !$producBundleItemTransfersCollection) {
                continue;
            }

            $returnCreateRequestTransfer = $this->appendReturnItemTransfers(
                $producBundleItemTransfersCollection,
                $productBundleData,
                $returnCreateRequestTransfer
            );
        }

        return $returnCreateRequestTransfer;
    }

    /**
     * @param array $producBundleItemTransfersCollection
     * @param array $productBundleData
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    protected function appendReturnItemTransfers(
        array $producBundleItemTransfersCollection,
        array $productBundleData,
        ReturnCreateRequestTransfer $returnCreateRequestTransfer
    ): ReturnCreateRequestTransfer {
        $productBundleitemTransfer = $productBundleData[ProductBundleItemsForm::PARAM_PRODUCT_BUNDLE_DATA];

        foreach ($producBundleItemTransfersCollection as $productBundleItem) {
            $itemTransfer = $productBundleItem[ProductBundleItemsForm::PARAM_ORDER_ITEM];

            if ($productBundleitemTransfer->getBundleItemIdentifier() != $itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            $returnItemTransfer = (new ReturnItemTransfer())->fromArray($productBundleItem, true);
            $returnItemTransfer->setReason($productBundleData[ReturnItemTransfer::REASON]);

            if (
                $productBundleData[ReturnItemTransfer::REASON] === static::CUSTOM_REASON_VALUE
                && $productBundleData[ProductBundleItemsForm::FIELD_CUSTOM_REASON]
            ) {
                $returnItemTransfer->setReason($productBundleData[ProductBundleItemsForm::FIELD_CUSTOM_REASON]);
            }

            $returnCreateRequestTransfer->getReturnItems()->append($returnItemTransfer);
        }

        return $returnCreateRequestTransfer;
    }
}
