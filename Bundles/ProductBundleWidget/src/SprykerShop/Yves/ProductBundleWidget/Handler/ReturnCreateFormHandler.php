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

class ReturnCreateFormHandler implements ReturnCreateFormHandlerInterface
{
    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Form\DataProvider\ReturnCreateFormDataProvider::CUSTOM_REASON_VALUE
     */
    protected const CUSTOM_REASON_VALUE = 'custom_reason';

    /**
     * @param array $returnItemList
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handleFormData(array $returnItemList, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer
    {
        $productBundleList = $returnItemList[ProductBundleItemsForm::FIELD_PRODUCT_BUNDLES] ?? [];
        $productBundleItemTransferCollection = $returnItemList[ProductBundleItemsForm::KEY_PRODUCT_BUNDLE_ITEMS] ?? [];

        if (!$productBundleItemTransferCollection) {
            return $returnCreateRequestTransfer;
        }

        foreach ($productBundleList as $productBundleData) {
            if (!$productBundleData[ItemTransfer::IS_RETURNABLE]) {
                continue;
            }

            $returnCreateRequestTransfer = $this->appendReturnItemTransfers(
                $productBundleItemTransferCollection,
                $productBundleData,
                $returnCreateRequestTransfer
            );
        }

        return $returnCreateRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $productBundleItemTransferCollection
     * @param array $productBundleData
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    protected function appendReturnItemTransfers(
        array $productBundleItemTransferCollection,
        array $productBundleData,
        ReturnCreateRequestTransfer $returnCreateRequestTransfer
    ): ReturnCreateRequestTransfer {
        $productBundleItemTransfer = $productBundleData[ProductBundleItemsForm::KEY_PRODUCT_BUNDLE_DATA];

        foreach ($productBundleItemTransferCollection as $itemTransfer) {
            if ($productBundleItemTransfer->getBundleItemIdentifier() !== $itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }

            $returnItemTransfer = (new ReturnItemTransfer())
                ->setReason($productBundleData[ReturnItemTransfer::REASON])
                ->setOrderItem($itemTransfer);

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
