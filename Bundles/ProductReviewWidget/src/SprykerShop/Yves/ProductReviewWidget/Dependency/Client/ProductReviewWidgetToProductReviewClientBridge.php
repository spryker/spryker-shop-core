<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;

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
}
