<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\BulkProductReviewSearchRequestBuilder;

use Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer;

interface BulkProductReviewSearchRequestBuilderInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer
     */
    public function createBulkProductReviewSearchRequestTransfer(array $productAbstractIds): BulkProductReviewSearchRequestTransfer;
}
