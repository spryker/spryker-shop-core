<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductVolumeWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\PriceProductVolumeWidget\Widget\ProductPriceVolumeWidget;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\VolumePriceProductWidget\PriceProductVolumeWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\PriceProductVolumeWidget\Widget\ProductPriceVolumeWidget instead.
 *
 * @method \SprykerShop\Yves\PriceProductVolumeWidget\PriceProductVolumeWidgetFactory getFactory()
 */
class PriceProductVolumeWidgetPlugin extends AbstractWidgetPlugin implements PriceProductVolumeWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $widget = new ProductPriceVolumeWidget($productViewTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName()
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
    public static function getTemplate()
    {
        return ProductPriceVolumeWidget::getTemplate();
    }
}
