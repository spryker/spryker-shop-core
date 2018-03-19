<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductMeasurementUnitWidget\ProductMeasurementUnitWidgetPluginInterface;

class ProductMeasurementUnitWidget extends AbstractWidgetPlugin implements ProductMeasurementUnitWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter('product', $productViewTransfer);
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
        return '@ProductMeasurementUnitWidget/views/product-measurement-unit/product-measurement-unit.twig';
    }
}
