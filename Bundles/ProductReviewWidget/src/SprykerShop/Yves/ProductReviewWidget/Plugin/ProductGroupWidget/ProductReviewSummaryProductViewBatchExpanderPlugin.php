<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\ProductGroupWidget;

use Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductGroupWidgetExtension\Dependency\Plugin\ProductViewBatchExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class ProductReviewSummaryProductViewBatchExpanderPlugin extends AbstractPlugin implements ProductViewBatchExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Batch expands `ProductViewTransfer` objects with product review rating data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function expandBatch(array $productViewTransfers): array
    {
        $bulkProductReviewSearchRequestTransfer = $this->createBulkProductReviewSearchRequestTransfer(
            $this->getProductAbstractIds($productViewTransfers)
        );

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

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer
     */
    protected function createBulkProductReviewSearchRequestTransfer(array $productAbstractIds): BulkProductReviewSearchRequestTransfer
    {
        return (new BulkProductReviewSearchRequestTransfer())
            ->setProductAbstractIds($productAbstractIds)
            ->setFilter(new FilterTransfer());
    }
}
