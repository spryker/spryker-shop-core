<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\ProductWidget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductWidget\Dependency\Plugin\ProductReviewWidget\ProductAbstractReviewWidgetPluginInterface;

class ProductAbstractReviewWidgetPlugin extends AbstractWidgetPlugin implements ProductAbstractReviewWidgetPluginInterface
{

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function initialize(int $idProductAbstract): void
    {
        $this->addParameter('idProductAbstract', $idProductAbstract);
    }

    /**
     * TODO: add specification
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * TODO: add specification
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductReviewWidget/_product_widget/product-abstract-review.twig';
    }

}
