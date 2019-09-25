<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReplacementForWidget\Plugin\ProductDetailPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductReplacementForWidgetPlugin\ProductReplacementForWidgetPluginInterface;
use SprykerShop\Yves\ProductReplacementForWidget\Widget\ProductReplacementForListWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductReplacementForWidget\Widget\ProductReplacementForListWidget instead.
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
        $widget = new ProductReplacementForListWidget($sku);

        $this->parameters = $widget->getParameters();

        $this->addWidgets($this->getFactory()->getProductDetailPageProductReplacementsForWidgetPlugins());
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return ProductReplacementForListWidget::getTemplate();
    }
}
