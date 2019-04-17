<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Widget;

use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
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
     */
    public function __construct(ProductViewTransfer $productViewTransfer, bool $isAddToCartDisabled)
    {
        $baseUnit = null;
        $salesUnits = null;
        $isAmountBlockEnabled = false;
        $productQuantityStorageTransfer = null;
        $leadProductSalesUnits = null;
        $productConcretePackagingStorageTransfer = null;
        $productConcreteAvailabilityTransfer = null;

        if ($productViewTransfer->getIdProductConcrete()) {
            $productMeasurementUnitStorageClient = $this->getFactory()->getProductMeasurementUnitStorageClient();
            $productPackagingUnitStorageClient = $this->getFactory()->getProductPackagingUnitStorageClient();
            $availabilityClient = $this->getFactory()->getAvailabilityClient();
            $availabilityRequestTransfer = new ProductConcreteAvailabilityRequestTransfer();
            $availabilityRequestTransfer->setSku($productViewTransfer->getSku());
            $productConcreteAvailabilityTransfer = $availabilityClient->findProductConcreteAvailability($availabilityRequestTransfer);

            $baseUnit = $productMeasurementUnitStorageClient->findProductMeasurementBaseUnitByIdProductConcrete($productViewTransfer->getIdProductConcrete());

            $productConcretePackagingStorageTransfer = $productPackagingUnitStorageClient->findProductConcretePackagingById(
                $productViewTransfer->getIdProductAbstract(),
                $productViewTransfer->getIdProductConcrete()
            );

            $productAbstractPackaging = $productPackagingUnitStorageClient->findProductAbstractPackagingById(
                $productViewTransfer->getIdProductAbstract()
            );

            $salesUnits = $productMeasurementUnitStorageClient->findProductMeasurementSalesUnitByIdProductConcrete(
                $productViewTransfer->getIdProductConcrete()
            );

            if ($productAbstractPackaging) {
                $leadProductSalesUnits = $productMeasurementUnitStorageClient->findProductMeasurementSalesUnitByIdProductConcrete(
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
        $this->setQuantityRestrictions($productQuantityStorageTransfer, $productConcreteAvailabilityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null $productConcreteAvailabilityTransfer
     *
     * @return void
     */
    protected function setQuantityRestrictions(
        ?ProductQuantityStorageTransfer $productQuantityStorageTransfer,
        ?ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): void {
        $minQuantity = $this->getMinQuantity($productQuantityStorageTransfer);
        $maxQuantity = $this->getMaxQuantity($productQuantityStorageTransfer, $productConcreteAvailabilityTransfer);
        $quantityInterval = $this->getQuantityInterval($productQuantityStorageTransfer);

        $this->addParameter('minQuantity', $minQuantity)
            ->addParameter('maxQuantity', $maxQuantity)
            ->addParameter('quantityInterval', $quantityInterval);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return float
     */
    protected function getQuantityInterval(?ProductQuantityStorageTransfer $productQuantityStorageTransfer): float
    {
        if ($productQuantityStorageTransfer === null) {
            return 1;
        }

        return $productQuantityStorageTransfer->getQuantityInterval();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null $productConcreteAvailabilityTransfer
     *
     * @return float|null
     */
    protected function getMaxQuantity(
        ?ProductQuantityStorageTransfer $productQuantityStorageTransfer,
        ?ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
    ): ?float {
        if ($productConcreteAvailabilityTransfer === null) {
            return 0;
        }

        if ($productQuantityStorageTransfer === null && $productConcreteAvailabilityTransfer->getIsNeverOutOfStock()) {
            return null;
        }

        $availability = $productConcreteAvailabilityTransfer->getAvailability();

        if (!$productConcreteAvailabilityTransfer->getIsNeverOutOfStock() && $productQuantityStorageTransfer === null) {
            return $availability;
        }

        return min($productQuantityStorageTransfer->getQuantityMax(), $availability);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return float
     */
    protected function getMinQuantity(?ProductQuantityStorageTransfer $productQuantityStorageTransfer): float
    {
        if ($productQuantityStorageTransfer === null) {
            return 1;
        }

        return $productQuantityStorageTransfer->getQuantityMin();
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
    ): string {
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
