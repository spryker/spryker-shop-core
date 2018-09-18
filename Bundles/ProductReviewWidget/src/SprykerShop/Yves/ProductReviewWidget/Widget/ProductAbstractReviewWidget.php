<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class ProductAbstractReviewWidget extends AbstractWidget
{
    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function initialize(int $idProductAbstract): void
    {
        $this
            ->addParameter('productReviewStorageTransfer', $this->findProductAbstractReview($idProductAbstract))
            ->addParameter('maximumRating', $this->getMaximumRating());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductAbstractReviewWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductReviewWidget/views/product-abstract-review-display/product-abstract-review-display.twig';
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer
     */
    protected function findProductAbstractReview($idProductAbstract)
    {
        return $this->getFactory()
            ->getProductReviewStorageClient()
            ->findProductAbstractReview($idProductAbstract);
    }

    /**
     * @return int
     */
    protected function getMaximumRating()
    {
        return $this->getFactory()
            ->getProductReviewClient()
            ->getMaximumRating();
    }
}
