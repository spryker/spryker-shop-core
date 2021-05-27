<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\BulkProductReviewSearchRequestBuilder;

use Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\FilterTransfer;

class BulkProductReviewSearchRequestBuilder implements BulkProductReviewSearchRequestBuilderInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer
     */
    public function createBulkProductReviewSearchRequestTransfer(array $productAbstractIds): BulkProductReviewSearchRequestTransfer
    {
        $productReviewSearchRequestTransfer = new BulkProductReviewSearchRequestTransfer();
        $productReviewSearchRequestTransfer->setProductAbstractIds($productAbstractIds);
        $productReviewSearchRequestTransfer->setFilter($this->createFilterTransfer());

        return $productReviewSearchRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(): FilterTransfer
    {
        return new FilterTransfer();
    }
}
