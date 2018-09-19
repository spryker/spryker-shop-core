<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductVolumeWidget\Widget;

use Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\PriceProductVolumeWidget\PriceProductVolumeWidgetFactory getFactory()
 */
class ProductPriceVolumeWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
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
    public static function getName(): string
    {
        return 'ProductPriceVolumeWidget';
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
