<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReplacementForWidget\Plugin\ProductDetailPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductReplacementForWidgetPlugin\ProductReplacementForWidgetPluginInterface;
use SprykerShop\Yves\ProductReplacementForWidget\Widget\ProductReplacementForWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductReplacementForWidget\Widget\ProductReplacementForWidget instead.
 *
 * @method \SprykerShop\Yves\ProductReplacementForWidget\ProductReplacementForWidgetFactory getFactory()
 */
class ProductReplacementForWidgetPlugin extends AbstractWidgetPlugin implements ProductReplacementForWidgetPluginInterface
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function initialize(string $sku): void
    {
        $widget = new ProductReplacementForWidget($sku);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return ProductReplacementForWidget::getTemplate();
    }
}
