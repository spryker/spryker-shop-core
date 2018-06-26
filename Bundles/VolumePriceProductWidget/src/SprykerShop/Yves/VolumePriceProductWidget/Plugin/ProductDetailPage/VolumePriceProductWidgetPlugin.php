<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\VolumePriceProductWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\VolumePriceProductWidget\VolumePriceProductWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\VolumePriceProductWidget\VolumePriceProductWidgetFactory getFactory()
 */
class VolumePriceProductWidgetPlugin extends AbstractWidgetPlugin implements VolumePriceProductWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $this
            ->addParameter(
                'volumeProductPrices',
                $this->findVolumeProductPrice($productViewTransfer)
            );
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
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
     * Specification:
     * - Returns the the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@VolumePriceProductWidget/views/volume-price-product-widget/volume-price-product.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer
     */
    protected function findVolumeProductPrice(ProductViewTransfer $productViewTransfer): VolumeProductPriceCollectionTransfer
    {
        return $this->getFactory()
            ->createVolumePriceProductResolver()
            ->resolveVolumePriceProduct($productViewTransfer);
    }
}
