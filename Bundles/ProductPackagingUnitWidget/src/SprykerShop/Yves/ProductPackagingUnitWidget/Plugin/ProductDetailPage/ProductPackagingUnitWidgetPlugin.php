<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Plugin\ProductDetailPage;

use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductPackagingUnitWidget\ProductPackagingUnitWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductPackagingUnitWidget\ProductPackagingUnitWidgetFactory getFactory()
 */
class ProductPackagingUnitWidgetPlugin extends AbstractWidgetPlugin implements ProductPackagingUnitWidgetPluginInterface
{
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
        return '@ProductPackagingUnitWidget/views/pdp-product-packaging-unit/pdp-product-packaging-unit.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isAddToCartDisabled
     * @param array $quantityOptions Contains the selectable quantity options; each option is structured as ['label' => 1, 'value' => 1]
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, bool $isAddToCartDisabled, array $quantityOptions = []): void
    {
        $baseUnit = null;
        $salesUnits = null;
        $isAmountBlockEnabled = false;
        $productQuantityStorageTransfer = null;
        $leadProductSalesUnits = null;
        $productConcretePackagingStorageTransfer = null;

        if ($productViewTransfer->getIdProductConcrete()) {
            $baseUnit = $this->getFactory()->getProductMeasurementUnitStorageClient()->findProductMeasurementBaseUnitByIdProductConcrete($productViewTransfer->getIdProductConcrete());

            $productConcretePackagingStorageTransfer = $this->getFactory()->getProductPackagingUnitStorageClient()->findProductConcretePackagingById(
                $productViewTransfer->getIdProductAbstract(),
                $productViewTransfer->getIdProductConcrete()
            );

            $productAbstractPackaging = $this->getFactory()->getProductPackagingUnitStorageClient()->findProductAbstractPackagingById(
                $productViewTransfer->getIdProductAbstract()
            );

            $salesUnits = $this->getFactory()->getProductMeasurementUnitStorageClient()->findProductMeasurementSalesUnitByIdProductConcrete(
                $productViewTransfer->getIdProductConcrete()
            );

            if ($productAbstractPackaging) {
                $leadProductSalesUnits = $this->getFactory()->getProductMeasurementUnitStorageClient()->findProductMeasurementSalesUnitByIdProductConcrete(
                    $productAbstractPackaging->getLeadProduct()
                );

                $isAmountBlockEnabled = $this->isAmountBlockEnabled(
                    $productViewTransfer->getIdProductConcrete(),
                    $productAbstractPackaging->getLeadProduct(),
                    $productConcretePackagingStorageTransfer->getHasLeadProduct()
                );
            }

            $productQuantityStorageTransfer = $this->getFactory()
                ->getProductQuantityStorageClient()
                ->findProductQuantityStorage($productViewTransfer->getIdProductConcrete());
        }

        $minQuantityInBaseUnit = $this->getMinQuantityInBaseUnit($productQuantityStorageTransfer);
        $minQuantityInSalesUnits = $this->getMinQuantityInSalesUnits($minQuantityInBaseUnit, $salesUnits);

        $this
            ->addParameter('product', $productViewTransfer)
            ->addParameter('quantityOptions', $quantityOptions)
            ->addParameter('minQuantityInBaseUnit', $minQuantityInBaseUnit)
            ->addParameter('minQuantityInSalesUnits', $minQuantityInSalesUnits)
            ->addParameter('baseUnit', $baseUnit)
            ->addParameter('salesUnits', $salesUnits)
            ->addParameter('leadProductSalesUnits', $leadProductSalesUnits)
            ->addParameter('productPackagingUnit', $productConcretePackagingStorageTransfer)
            ->addParameter('isAddToCartDisabled', $isAddToCartDisabled)
            ->addParameter('isAmountBlockEnabled', $isAmountBlockEnabled)
            ->addParameter('productQuantityStorage', $productQuantityStorageTransfer)
            ->addParameter('jsonScheme', $this->prepareJsonData(
                $isAmountBlockEnabled,
                $isAddToCartDisabled,
                $baseUnit,
                $salesUnits,
                $leadProductSalesUnits,
                $productConcretePackagingStorageTransfer,
                $productQuantityStorageTransfer
            ));
    }

    /**
     * @param bool $isAmountBlockEnabled
     * @param bool $isAddToCartDisabled
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null $baseUnit
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null $salesUnits
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null $leadSalesUnits
     * @param \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer|null $productConcretePackagingStorageTransfer
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return string
     */
    protected function prepareJsonData(
        bool $isAmountBlockEnabled,
        bool $isAddToCartDisabled,
        ?ProductMeasurementUnitTransfer $baseUnit,
        ?array $salesUnits,
        ?array $leadSalesUnits,
        ?ProductConcretePackagingStorageTransfer $productConcretePackagingStorageTransfer,
        ?ProductQuantityStorageTransfer $productQuantityStorageTransfer = null
    ) {
        $jsonData = [];

        $jsonData['isAddToCartDisabled'] = $isAddToCartDisabled;
        $jsonData['isAmountBlockEnabled'] = $isAmountBlockEnabled;

        if ($baseUnit !== null) {
            $jsonData['baseUnit'] = $baseUnit->toArray();
        }

        foreach ((array)$salesUnits as $salesUnit) {
            $jsonData['salesUnits'][] = $salesUnit->toArray();
        }

        foreach ((array)$leadSalesUnits as $leadSalesUnit) {
            $jsonData['leadSalesUnits'][] = $leadSalesUnit->toArray();
        }

        if ($productConcretePackagingStorageTransfer !== null) {
            $jsonData['productPackagingUnitStorage'] = $productConcretePackagingStorageTransfer->toArray();
        }

        if ($productQuantityStorageTransfer !== null) {
            $jsonData['productQuantityStorage'] = $productQuantityStorageTransfer->toArray();
        }

        return $this->getFactory()
            ->getUtilEncodingService()
            ->encodeJson($jsonData);
    }

    /**
     * @param int $minQuantityInBaseUnits
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null $salesUnits
     *
     * @return float
     */
    protected function getMinQuantityInSalesUnits(int $minQuantityInBaseUnits, ?array $salesUnits = null): float
    {
        if ($salesUnits === null) {
            return $minQuantityInBaseUnits;
        }

        foreach ($salesUnits as $salesUnit) {
            if ($salesUnit->getIsDefault() && $salesUnit->getConversion()) {
                $qtyInSalesUnits = $minQuantityInBaseUnits / $salesUnit->getConversion();

                return round($qtyInSalesUnits, 4);
            }
        }

        return $minQuantityInBaseUnits;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return int
     */
    protected function getMinQuantityInBaseUnit(
        ?ProductQuantityStorageTransfer $productQuantityStorageTransfer = null
    ): int {
        $quantityMin = 1;
        if ($productQuantityStorageTransfer !== null) {
            $quantityMin = $productQuantityStorageTransfer->getQuantityMin() ?: 1;
        }

        return $quantityMin;
    }

    /**
     * @param int $idProduct
     * @param int $idLeadProduct
     * @param bool $hasLeadProduct
     *
     * @return bool
     */
    protected function isAmountBlockEnabled(int $idProduct, int $idLeadProduct, bool $hasLeadProduct): bool
    {
        return ($idProduct !== $idLeadProduct) && $hasLeadProduct;
    }
}
