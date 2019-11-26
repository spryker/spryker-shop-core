<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\ProductReviewSummaryTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class ProductReviewWidgetToProductReviewClientBridge implements ProductReviewWidgetToProductReviewClientInterface
{
    /**
     * @var \Spryker\Client\ProductReview\ProductReviewClientInterface
     */
    protected $productReviewClient;

    /**
     * @param \Spryker\Client\ProductReview\ProductReviewClientInterface $productReviewClient
     */
    public function __construct($productReviewClient)
    {
        $this->productReviewClient = $productReviewClient;
    }

    /**
     * @return int
     */
    public function getMaximumRating()
    {
        return $this->productReviewClient->getMaximumRating();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return array
     */
    public function findProductReviewsInSearch(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer)
    {
        return $this->productReviewClient->findProductReviewsInSearch($productReviewSearchRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewRequestTransfer $productReviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewResponseTransfer
     */
    public function submitCustomerReview(ProductReviewRequestTransfer $productReviewRequestTransfer)
    {
        return $this->productReviewClient->submitCustomerReview($productReviewRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductReviewData(
        ProductViewTransfer $productViewTransfer,
        ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
    ): ProductViewTransfer {
        return $this->productReviewClient
            ->expandProductViewWithProductReviewData($productViewTransfer, $productReviewSearchRequestTransfer);
    }

    /**
     * @param array $ratingAggregation
     *
     * @return \Generated\Shared\Transfer\ProductReviewSummaryTransfer
     */
    public function calculateProductReviewSummary(array $ratingAggregation): ProductReviewSummaryTransfer
    {
        return $this->productReviewClient->calculateProductReviewSummary($ratingAggregation);
    }
}
