<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Plugin\ProductWidget;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductReviewWidget\Widget\ProductAbstractReviewWidget;
use SprykerShop\Yves\ProductWidget\Dependency\Plugin\ProductReviewWidget\ProductAbstractReviewWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductReviewWidget\Widget\ProductAbstractReviewWidget instead.
 *
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class ProductAbstractReviewWidgetPlugin extends AbstractWidgetPlugin implements ProductAbstractReviewWidgetPluginInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function initialize(int $idProductAbstract): void
    {
        $widget = new ProductAbstractReviewWidget($idProductAbstract);

        $this->parameters = $widget->getParameters();
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
        return ProductAbstractReviewWidget::getTemplate();
    }
}
