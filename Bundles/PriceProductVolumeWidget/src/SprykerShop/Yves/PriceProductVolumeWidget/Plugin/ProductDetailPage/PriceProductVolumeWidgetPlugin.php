<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductVolumeWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\VolumePriceProductWidget\PriceProductVolumeWidgetPluginInterface;

/**
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
        $this
            ->addParameter('product', $productViewTransfer)
            ->addParameter(
                'volumeProductPrices',
                $this->findPriceProductVolume($productViewTransfer)
            );
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@PriceProductVolumeWidget/views/volume-price-product-widget/volume-price-product.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer
     */
    protected function findPriceProductVolume(ProductViewTransfer $productViewTransfer): PriceProductVolumeCollectionTransfer
    {
        return $this->getFactory()
            ->createPriceProductVolumeResolver()
            ->resolveVolumeProductPrices($productViewTransfer);
    }
}
