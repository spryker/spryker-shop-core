<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\ProductGroupWidget;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductGroupWidgetExtension\Dependency\Plugin\ProductViewBatchExpanderPluginInterface;

class ProductReviewSummaryProductViewBatchExpanderPlugin extends AbstractPlugin implements ProductViewBatchExpanderPluginInterface
{
    /**
     * Specification:
     *  - Batch expands product view data transfer objects with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function expandBatch(array $productViewTransfers): array
    {
        $productAbstractIds = $this->getProductAbstractIds($productViewTransfers);

        $bulkProductReviewSearchRequestTransfer = $this->getFactory()
            ->createBulkProductReviewSearchRequestBuilder()
            ->createBulkProductReviewSearchRequestTransfer($productAbstractIds);

        $productViewTransfers = $this->getFactory()
            ->getProductReviewClient()
            ->expandProductViewBatchWithProductReviewData($productViewTransfers, $bulkProductReviewSearchRequestTransfer);

        return $productViewTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return int[]
     */
    protected function getProductAbstractIds(array $productViewTransfers): array
    {
        $ids = [];
        foreach ($productViewTransfers as $productViewTransfer) {
            $ids[] = $productViewTransfer->getIdProductAbstract();
        }

        return $ids;
    }
}
