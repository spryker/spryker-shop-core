<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductAlternativeWidget\Widget\ProductAlternativeListWidget;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductAlternativeWidget\ProductAlternativeWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductAlternativeWidget\Widget\ProductAlternativeListWidget instead.
 *
 * @method \SprykerShop\Yves\ProductAlternativeWidget\ProductAlternativeWidgetFactory getFactory()
 */
class ProductAlternativeWidgetPlugin extends AbstractWidgetPlugin implements ProductAlternativeWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $widget = new ProductAlternativeListWidget($productViewTransfer);

        $this->parameters = $widget->getParameters();

        $this->addWidgets($this->getFactory()->getProductDetailPageProductAlternativeWidgetPlugins());
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
        return ProductAlternativeListWidget::getTemplate();
    }
}
