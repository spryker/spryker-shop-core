<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductWidget\Plugin\ProductReplacementForWidget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductReplacementForWidget\Dependency\Plugin\ProductWidget\ProductWidgetPluginInterface;
use SprykerShop\Yves\ProductWidget\Widget\PdpProductReplacementForListWidget;

/**
 * @deprecated Use {@link \SprykerShop\Yves\ProductWidget\Widget\PdpProductReplacementForListWidget} instead.
 *
 * @method \SprykerShop\Yves\ProductWidget\ProductWidgetFactory getFactory()
 */
class ProductWidgetPlugin extends AbstractWidgetPlugin implements ProductWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $widget = new PdpProductReplacementForListWidget($productViewTransfer);

        $this->parameters = $widget->getParameters();

        $this->addWidgets($this->getFactory()->getProductReplacementForWidgetPlugins());
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
        return PdpProductReplacementForListWidget::getTemplate();
    }
}
