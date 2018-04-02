<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductConcreteMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
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
        $salesUnits = [];
        $baseUnit = null;
        $productQuantityStorageTransfer = null;

        if ($productViewTransfer->getIdProductConcrete()) {
            $productConcreteMeasurementUnitStorageTransfer = $this->getFactory()
                ->getProductMeasurementUnitStorageClient()
                ->findProductConcreteMeasurementUnitStorage($productViewTransfer->getIdProductConcrete());

            if ($productConcreteMeasurementUnitStorageTransfer !== null) {
                $baseUnit = $this->prepareBaseUnit($productConcreteMeasurementUnitStorageTransfer->getBaseUnit());

                $salesUnits = $this->prepareSalesUnits(
                    $productConcreteMeasurementUnitStorageTransfer->getSalesUnits()->getArrayCopy()
                );
            }
            $productQuantityStorageTransfer = $this->findProductQuantityStorageTransfer(
                $productViewTransfer->getIdProductConcrete()
            );
        }


        $this
            ->addParameter('product', $productViewTransfer)
            ->addParameter('qtyOptions', $qtyOptions)
            ->addParameter('baseUnit', $baseUnit)
            ->addParameter('salesUnits', $salesUnits)
            ->addParameter('productQuantityStorage', $productQuantityStorageTransfer)
            ->addParameter(
                'jsonScheme',
                $this->prepareJsonData(
                    $baseUnit,
                    $salesUnits,
                    $productQuantityStorageTransfer
                )
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
        return '@ProductMeasurementUnitWidget/views/product-measurement-unit/product-measurement-unit.twig';
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null
     */
    protected function findProductQuantityStorageTransfer(int $idProduct): ?ProductQuantityStorageTransfer
    {
        return $this->getFactory()
            ->getProductQuantityStorageClient()
            ->findProductQuantityStorage($idProduct);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteMeasurementBaseUnitTransfer $productConcreteMeasurementBaseUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null
     */
    protected function prepareBaseUnit(
        ProductConcreteMeasurementBaseUnitTransfer $productConcreteMeasurementBaseUnit
    ): ?ProductMeasurementUnitTransfer {
        $productMeasurementUnitStorageTransfer = $this->getFactory()
            ->getProductMeasurementUnitStorageClient()
            ->findProductMeasurementUnitStorage($productConcreteMeasurementBaseUnit->getIdProductMeasurementUnit());

        if ($productMeasurementUnitStorageTransfer !== null) {
            return $this->getFactory()
                ->createProductMeasurementSalesUnitMapper()
                ->mapProductMeasurementUnit(
                    $productMeasurementUnitStorageTransfer,
                    new ProductMeasurementUnitTransfer()
                );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteMeasurementSalesUnitTransfer[] $productConcreteMeasurementSalesUnits
     *
     * @return array
     */
    protected function prepareSalesUnits(
        array $productConcreteMeasurementSalesUnits = []
    ): array {
        $saleUnits = [];
        $mapper = $this->getFactory()->createProductMeasurementSalesUnitMapper();
        foreach ($productConcreteMeasurementSalesUnits as $productConcreteMeasurementSalesUnitTransfer) {
            if ($productConcreteMeasurementSalesUnitTransfer->getIsDisplayed() !== true) {
                //continue;
            }

            $productMeasurementUnitStorageTransfer = $this->getFactory()
                ->getProductMeasurementUnitStorageClient()
                ->findProductMeasurementUnitStorage($productConcreteMeasurementSalesUnitTransfer->getIdProductMeasurementUnit());

            if ($productMeasurementUnitStorageTransfer !== null) {
                $productMeasurementSalesUnitTransfer = $mapper->mapProductMeasurementSalesUnitTransfer(
                    $productConcreteMeasurementSalesUnitTransfer,
                    new ProductMeasurementSalesUnitTransfer()
                );

                $productMeasurementSalesUnitTransfer->setProductMeasurementUnit(
                    $mapper->mapProductMeasurementUnit(
                        $productMeasurementUnitStorageTransfer,
                        new ProductMeasurementUnitTransfer()
                    )
                );

                $saleUnits[] = $productMeasurementSalesUnitTransfer;
            }
        }

        return $saleUnits;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null $baseUnit
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[] $salesUnits
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return string
     */
    protected function prepareJsonData(
        ProductMeasurementUnitTransfer $baseUnit = null,
        array $salesUnits = [],
        ProductQuantityStorageTransfer $productQuantityStorageTransfer = null
    ): string {
        $jsonData = [];

        if ($baseUnit !== null) {
            $jsonData['baseUnit'] = $baseUnit->toArray();
        }
        foreach ($salesUnits as $salesUnit) {
            $jsonData['salesUnits'][] = $salesUnit->toArray();
        }

        if ($productQuantityStorageTransfer !== null) {
            $jsonData['productQuantityStorage'] = $productQuantityStorageTransfer->toArray();
        }

        return \json_encode($jsonData, true);
    }
}
