<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\ProductReviewSearchRequestBuilder;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Symfony\Component\HttpFoundation\Request;

class ProductReviewSearchRequestBuilder implements ProductReviewSearchRequestBuilderInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $applicationRequest;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $applicationRequest
     */
    public function __construct(Request $applicationRequest)
    {
        $this->applicationRequest = $applicationRequest;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer
     */
    public function createProductReviewSearchRequestTransfer(int $idProductAbstract): ProductReviewSearchRequestTransfer
    {
        $productReviewSearchRequestTransfer = new ProductReviewSearchRequestTransfer();
        $productReviewSearchRequestTransfer->setIdProductAbstract($idProductAbstract);
        $productReviewSearchRequestTransfer->setRequestParams($this->applicationRequest->query->all());

        return $productReviewSearchRequestTransfer;
    }
}
