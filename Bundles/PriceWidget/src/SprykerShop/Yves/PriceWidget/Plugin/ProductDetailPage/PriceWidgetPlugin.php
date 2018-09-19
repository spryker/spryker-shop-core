<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\PriceWidget\Widget\PriceWidget;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\PriceWidget\PriceWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\PriceWidget\Widget\PriceWidget instead.
 *
 * @method \SprykerShop\Yves\PriceWidget\PriceWidgetFactory getFactory()
 */
class PriceWidgetPlugin extends AbstractWidgetPlugin implements PriceWidgetPluginInterface
{
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
        return PriceWidget::getTemplate();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $widget = new PriceWidget($productViewTransfer);

        $this->parameters = $widget->getParameters();
    }
}
