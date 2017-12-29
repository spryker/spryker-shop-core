<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\ProductDetailPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductReviewWidget\ProductReviewWidgetPluginInterface;
use SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory;

/**
 * @method ProductReviewWidgetFactory getFactory()
 */
class ProductReviewWidgetPlugin extends AbstractWidgetPlugin implements ProductReviewWidgetPluginInterface
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
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductReviewWidget/_product-detail-page/product-review.twig';
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
