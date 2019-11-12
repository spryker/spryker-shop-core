<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Widget;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductPackagingUnitWidget\ProductPackagingUnitWidgetFactory getFactory()
 */
class ProductPackagingUnitWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isAddToCartDisabled
     * @param array $quantityOptions Contains the selectable quantity options; each option is structured as ['label' => 1, 'value' => 1]
     */
    public function __construct(ProductViewTransfer $productViewTransfer, bool $isAddToCartDisabled, array $quantityOptions = [])
    {
        $baseUnit = null;
        $salesUnits = null;
        $productQuantityStorageTransfer = null;
        $leadProductSalesUnits = null;
        $productPackagingUnitStorageTransfer = null;

        if ($productViewTransfer->getIdProductConcrete()) {
            $productMeasurementUnitStorageClient = $this->getFactory()->getProductMeasurementUnitStorageClient();
            $productPackagingUnitStorageClient = $this->getFactory()->getProductPackagingUnitStorageClient();

            $baseUnit = $productMeasurementUnitStorageClient->findProductMeasurementBaseUnitByIdProduct($productViewTransfer->getIdProductConcrete());

            $productPackagingUnitStorageTransfer = $productPackagingUnitStorageClient->findProductPackagingUnitById(
                $productViewTransfer->getIdProductConcrete()
            );

            $salesUnits = $productMeasurementUnitStorageClient->findProductMeasurementSalesUnitByIdProduct(
                $productViewTransfer->getIdProductConcrete()
            );

            if ($productPackagingUnitStorageTransfer) {
                $leadProductSalesUnits = $productMeasurementUnitStorageClient->findProductMeasurementSalesUnitByIdProduct(
                    $productPackagingUnitStorageTransfer->getIdLeadProduct()
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
            ->addParameter('productPackagingUnit', $productPackagingUnitStorageTransfer)
            ->addParameter('isAddToCartDisabled', $isAddToCartDisabled)
            ->addParameter('productQuantityStorage', $productQuantityStorageTransfer)
            ->addParameter('jsonScheme', $this->prepareJsonData(
                $isAddToCartDisabled,
                $baseUnit,
                $salesUnits,
                $leadProductSalesUnits,
                $productPackagingUnitStorageTransfer,
                $productQuantityStorageTransfer
            ));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductPackagingUnitWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductPackagingUnitWidget/views/pdp-product-packaging-unit/pdp-product-packaging-unit.twig';
    }

    /**
     * @param bool $isAddToCartDisabled
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null $baseUnit
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null $salesUnits
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null $leadSalesUnits
     * @param \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer|null $productPackagingUnitStorageTransfer
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return string
     */
    protected function prepareJsonData(
        bool $isAddToCartDisabled,
        ?ProductMeasurementUnitTransfer $baseUnit,
        ?array $salesUnits,
        ?array $leadSalesUnits,
        ?ProductPackagingUnitStorageTransfer $productPackagingUnitStorageTransfer,
        ?ProductQuantityStorageTransfer $productQuantityStorageTransfer = null
    ): string {
        $jsonData = [];

        $jsonData['isAddToCartDisabled'] = $isAddToCartDisabled;

        if ($baseUnit !== null) {
            $jsonData['baseUnit'] = $baseUnit->toArray();
        }

        foreach ((array)$salesUnits as $salesUnit) {
            $jsonData['salesUnits'][] = $salesUnit->toArray();
        }

        foreach ((array)$leadSalesUnits as $leadSalesUnit) {
            $jsonData['leadSalesUnits'][] = $leadSalesUnit->toArray();
        }

        if ($productPackagingUnitStorageTransfer !== null) {
            $jsonData['productPackagingUnitStorage'] = $productPackagingUnitStorageTransfer->toArray();
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
}
