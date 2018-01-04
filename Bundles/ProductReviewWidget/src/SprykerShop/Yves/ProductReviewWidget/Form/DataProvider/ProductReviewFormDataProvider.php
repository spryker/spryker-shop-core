<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Form\DataProvider;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;

class ProductReviewFormDataProvider
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewRequestTransfer
     */
    public function getData($idProductAbstract)
    {
        $productReviewTransfer = new ProductReviewRequestTransfer();
        $productReviewTransfer->setIdProductAbstract($idProductAbstract);

        return $productReviewTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => ProductReviewRequestTransfer::class,
        ];
    }
}
