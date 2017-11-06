<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\ProductWidget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductWidget\Dependency\Plugin\ProductReviewWidget\ProductReviewWidgetPluginInterface;

class ProductReviewWidgetPlugin extends AbstractWidgetPlugin implements ProductReviewWidgetPluginInterface
{

    /**
     * @param float $rating
     *
     * @return void
     */
    public function initialize(float $rating): void
    {
        $this->addParameter('rating', $rating);
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
        return '@ProductReviewWidget/_product-widget/product-review.twig';
    }

}
