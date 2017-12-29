<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Dependency\Client;

class ProductReviewWidgetToProductReviewStorageClientBridge implements ProductReviewWidgetToProductReviewStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductReviewStorage\ProductReviewStorageClientInterface
     */
    protected $productReviewStorageClient;

    /**
     * @param \Spryker\Client\ProductReviewStorage\ProductReviewStorageClientInterface $productReviewStorageClient
     */
    public function __construct($productReviewStorageClient)
    {
        $this->productReviewStorageClient = $productReviewStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer
     */
    public function findProductAbstractReview($idProductAbstract)
    {
        return $this->productReviewStorageClient->findProductAbstractReview($idProductAbstract);
    }
}
