<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class ProductReviewControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_PRODUCT_REVIEW_INDEX = 'product-review/index';
    public const ROUTE_PRODUCT_REVIEW_SUBMIT = 'product-review/submit';

    public const ID_ABSTRACT_PRODUCT_REGEX = '[1-9][0-9]*';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addProductReviewRoute()
            ->addProductReviewSubmitRoute();
    }

    /**
     * @return $this
     */
    protected function addProductReviewRoute(): self
    {
        $this->createController('/{productReview}/index/{idProductAbstract}', static::ROUTE_PRODUCT_REVIEW_INDEX, 'ProductReviewWidget', 'Index', 'index')
            ->assert('productReview', $this->getAllowedLocalesPattern() . 'product-review|product-review')
            ->value('productReview', 'product-review')
            ->assert('idProductAbstract', static::ID_ABSTRACT_PRODUCT_REGEX);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addProductReviewSubmitRoute(): self
    {
        $this->createController('/{productReview}/submit/{idProductAbstract}', static::ROUTE_PRODUCT_REVIEW_SUBMIT, 'ProductReviewWidget', 'Submit', 'index')
            ->assert('productReview', $this->getAllowedLocalesPattern() . 'product-review|product-review')
            ->value('productReview', 'product-review')
            ->assert('idProductAbstract', static::ID_ABSTRACT_PRODUCT_REGEX);

        return $this;
    }
}
