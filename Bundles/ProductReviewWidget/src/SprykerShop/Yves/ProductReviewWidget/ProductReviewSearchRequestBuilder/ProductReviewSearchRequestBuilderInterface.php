<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\ProductReviewSearchRequestBuilder;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;

interface ProductReviewSearchRequestBuilderInterface
{
    /**
     * @param int $idProductAbstract
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer
     */
    public function createProductReviewSearchRequestTransfer(int $idProductAbstract, array $params = []): ProductReviewSearchRequestTransfer;
}
