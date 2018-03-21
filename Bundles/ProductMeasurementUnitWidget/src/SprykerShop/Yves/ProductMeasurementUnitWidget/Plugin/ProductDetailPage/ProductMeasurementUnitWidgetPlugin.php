<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductMeasurementUnitWidget\ProductMeasurementUnitWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductMeasurementUnitWidget\ProductMeasurementUnitWidgetFactory getFactory()
 */
class ProductMeasurementUnitWidgetPlugin extends AbstractWidgetPlugin implements ProductMeasurementUnitWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $qtyOptions
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, array $qtyOptions = []): void
    {
        $productConcreteMeasurementUnitStorage = $this->getProductConcreteMeasurementUnitStorageTransfer(
            $productViewTransfer->getIdProductConcrete()
        );

        $this
            ->addParameter('product', $productViewTransfer)
            ->addParameter('qtyOptions', $qtyOptions)
            ->addParameter('productConcreteMeasurementUnits', $productConcreteMeasurementUnitStorage);
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

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer|null
     */
    protected function getProductConcreteMeasurementUnitStorageTransfer(
        int $idProductConcrete
    ): ?ProductConcreteMeasurementUnitStorageTransfer {
        return $this->getFactory()
            ->getProductMeasurementUnitStorageClient()
            ->getProductConcreteMeasurementUnit($idProductConcrete);
    }
}
