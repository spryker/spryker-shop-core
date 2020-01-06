<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Widget;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductMeasurementUnitWidget\ProductMeasurementUnitWidgetFactory getFactory()
 */
class ManageProductMeasurementUnitWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isAddToCartDisabled
     * @param array $quantityOptions
     */
    public function __construct(ProductViewTransfer $productViewTransfer, bool $isAddToCartDisabled, array $quantityOptions = [])
    {
        $salesUnits = null;
        $baseUnit = null;
        $productQuantityStorageTransfer = null;

        if ($productViewTransfer->getIdProductConcrete()) {
            $baseUnit = $this->getFactory()
                ->getProductMeasurementUnitStorageClient()
                ->findProductMeasurementBaseUnitByIdProductConcrete($productViewTransfer->getIdProductConcrete());

            $salesUnits = $this->getFactory()
                ->getProductMeasurementUnitStorageClient()
                ->findProductMeasurementSalesUnitByIdProductConcrete($productViewTransfer->getIdProductConcrete());

            $productQuantityStorageTransfer = $this->getFactory()
                ->getProductQuantityStorageClient()
                ->findProductQuantityStorage($productViewTransfer->getIdProductConcrete());
        }

        $minQuantityInBaseUnits = $this->getMinQuantityInBaseUnits($productQuantityStorageTransfer);
        $minQuantityInSalesUnits = $this->getMinQuantityInSalesUnits($minQuantityInBaseUnits, $salesUnits);
        $jsonSchema = $this->prepareJsonData($baseUnit, $salesUnits, $productQuantityStorageTransfer);

        $this->addParameter('product', $productViewTransfer)
            ->addParameter('quantityOptions', $quantityOptions)
            ->addParameter('minQuantityInBaseUnits', $minQuantityInBaseUnits)
            ->addParameter('minQuantityInSalesUnits', $minQuantityInSalesUnits)
            ->addParameter('baseUnit', $baseUnit)
            ->addParameter('salesUnits', $salesUnits)
            ->addParameter('isAddToCartDisabled', $isAddToCartDisabled)
            ->addParameter('jsonScheme', $jsonSchema);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return int
     */
    protected function getMinQuantityInBaseUnits(
        ?ProductQuantityStorageTransfer $productQuantityStorageTransfer = null
    ): int {
        $quantityMin = 1;
        if ($productQuantityStorageTransfer !== null) {
            $quantityMin = $productQuantityStorageTransfer->getQuantityMin() ?: 1;
        }

        return $quantityMin;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ManageProductMeasurementUnitWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductMeasurementUnitWidget/views/pdp-product-measurement-unit/pdp-product-measurement-unit.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null $baseUnit
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null $salesUnits
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return string
     */
    protected function prepareJsonData(
        ?ProductMeasurementUnitTransfer $baseUnit = null,
        ?array $salesUnits = null,
        ?ProductQuantityStorageTransfer $productQuantityStorageTransfer = null
    ): string {
        $jsonData = [];

        if ($baseUnit !== null) {
            $jsonData['baseUnit'] = $baseUnit->toArray();
        }

        if ($salesUnits !== null) {
            foreach ($salesUnits as $salesUnit) {
                $jsonData['salesUnits'][] = $salesUnit->toArray();
            }
        }

        if ($productQuantityStorageTransfer !== null) {
            $jsonData['productQuantityStorage'] = $productQuantityStorageTransfer->toArray();
        }

        return $this->getFactory()->getUtilEncodingService()->encodeJson($jsonData, JSON_HEX_TAG);
    }

    /**
     * @param int $minQuantityInBaseUnits
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null $salesUnits
     *
     * @return float
     */
    protected function getMinQuantityInSalesUnits(int $minQuantityInBaseUnits, ?array $salesUnits = null): float
    {
        if ($salesUnits !== null) {
            foreach ($salesUnits as $salesUnit) {
                if ($salesUnit->getIsDefault()) {
                    $qtyInSalesUnits = $minQuantityInBaseUnits / $salesUnit->getConversion();

                    return round($qtyInSalesUnits, 4);
                }
            }
        }

        return (float)$minQuantityInBaseUnits;
    }
}
