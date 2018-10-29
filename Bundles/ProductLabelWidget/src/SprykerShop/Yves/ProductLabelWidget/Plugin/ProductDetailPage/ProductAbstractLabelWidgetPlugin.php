<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductLabelWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductLabelWidget\ProductLabelWidgetPluginInterface;
use SprykerShop\Yves\ProductLabelWidget\Widget\ProductAbstractLabelWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductLabelWidget\Widget\ProductAbstractLabelWidget instead.
 *
 * @method \SprykerShop\Yves\ProductLabelWidget\ProductLabelWidgetFactory getFactory()
 */
class ProductAbstractLabelWidgetPlugin extends AbstractWidgetPlugin implements ProductLabelWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $widget = new ProductAbstractLabelWidget($productViewTransfer->getIdProductAbstract());

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
        return ProductAbstractLabelWidget::getTemplate();
    }
}
