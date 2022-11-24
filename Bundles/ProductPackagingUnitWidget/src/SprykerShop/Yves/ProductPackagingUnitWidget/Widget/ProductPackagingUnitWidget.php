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
     * @var string
     */
    protected const PARAMETER_PRODUCT = 'product';

    /**
     * @var string
     */
    protected const PARAMETER_QUANTITY_OPTIONS = 'quantityOptions';

    /**
     * @var string
     */
    protected const PARAMETER_MIN_QUANTITY_IN_BASE_UNIT = 'minQuantityInBaseUnit';

    /**
     * @var string
     */
    protected const PARAMETER_MIN_QUANTITY_IN_SALES_UNITS = 'minQuantityInSalesUnits';

    /**
     * @var string
     */
    protected const PARAMETER_BASE_UNIT = 'baseUnit';

    /**
     * @var string
     */
    protected const PARAMETER_SALES_UNIT = 'salesUnits';

    /**
     * @var string
     */
    protected const PARAMETER_LEAD_PRODUCT_SALES_UNIT = 'leadProductSalesUnits';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_PACKAGING_UNIT = 'productPackagingUnit';

    /**
     * @var string
     */
    protected const PARAMETER_PACKAGING_UNIT_IS_SELF_LEAD = 'packagingUnitIsSelfLead';

    /**
     * @var string
     */
    protected const PARAMETER_IS_ADD_TO_CART_DISABLED = 'isAddToCartDisabled';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_QUANTITY_STORAGE = 'productQuantityStorage';

    /**
     * @var string
     */
    protected const PARAMETER_JSON_SCHEME = 'jsonScheme';

    /**
     * @var string
     */
    protected const PARAMETER_NUMBER_FORMAT_CONFIG = 'numberFormatConfig';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isAddToCartDisabled
     * @param array<string, mixed> $quantityOptions Contains the selectable quantity options; each option is structured as ['label' => 1, 'value' => 1]
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
                $productViewTransfer->getIdProductConcrete(),
            );

            $salesUnits = $productMeasurementUnitStorageClient->findProductMeasurementSalesUnitByIdProduct(
                $productViewTransfer->getIdProductConcrete(),
            );

            if ($productPackagingUnitStorageTransfer) {
                $leadProductSalesUnits = $productMeasurementUnitStorageClient->findProductMeasurementSalesUnitByIdProduct(
                    $productPackagingUnitStorageTransfer->getIdLeadProduct(),
                );
            }

            $productQuantityStorageTransfer = $this->getFactory()
                ->getProductQuantityStorageClient()
                ->findProductQuantityStorage($productViewTransfer->getIdProductConcrete());
        }

        $minQuantityInBaseUnit = $this->getMinQuantityInBaseUnit($productQuantityStorageTransfer);

        $this->addProductParameter($productViewTransfer);
        $this->addQuantityOptionsParameter($quantityOptions);
        $this->addMinQuantityInBaseUnitParameter($minQuantityInBaseUnit);
        $this->addMinQuantityInSalesUnitsParameter($this->getMinQuantityInSalesUnits($minQuantityInBaseUnit, $salesUnits));
        $this->addBaseUnitParameter($baseUnit);
        $this->addSalesUnitsParameter($salesUnits);
        $this->addLeadProductSalesUnitsParameter($leadProductSalesUnits);
        $this->addProductPackagingUnitParameter($productPackagingUnitStorageTransfer);
        $this->addPackagingUnitIsSelfLeadParameter($productPackagingUnitStorageTransfer);
        $this->addIsAddToCartDisabledParameter($isAddToCartDisabled);
        $this->addProductQuantityStorage($productQuantityStorageTransfer);
        $this->addNumberFormatConfigParameter();
        $this->addJsonSchemeParameter(
            $isAddToCartDisabled,
            $baseUnit,
            $salesUnits,
            $leadProductSalesUnits,
            $productPackagingUnitStorageTransfer,
            $productQuantityStorageTransfer,
        );
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
     * @param array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>|null $salesUnits
     * @param array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>|null $leadSalesUnits
     * @param \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer|null $productPackagingUnitStorageTransfer
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return void
     */
    protected function addJsonSchemeParameter(
        bool $isAddToCartDisabled,
        ?ProductMeasurementUnitTransfer $baseUnit,
        ?array $salesUnits,
        ?array $leadSalesUnits,
        ?ProductPackagingUnitStorageTransfer $productPackagingUnitStorageTransfer,
        ?ProductQuantityStorageTransfer $productQuantityStorageTransfer = null
    ): void {
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

        $this->addParameter(
            static::PARAMETER_JSON_SCHEME,
            $this->getFactory()
                ->getUtilEncodingService()
                ->encodeJson($jsonData),
        );
    }

    /**
     * @param int $minQuantityInBaseUnits
     * @param array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>|null $salesUnits
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
     * @param \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer|null $productPackagingUnitStorageTransfer
     *
     * @return bool
     */
    protected function isProductPackagingUnitSelfLead(?ProductPackagingUnitStorageTransfer $productPackagingUnitStorageTransfer): bool
    {
        return $productPackagingUnitStorageTransfer !== null
            && $productPackagingUnitStorageTransfer->getIdProduct() === $productPackagingUnitStorageTransfer->getIdLeadProduct();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addProductParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT, $productViewTransfer);
    }

    /**
     * @param array<string, mixed> $quantityOptions
     *
     * @return void
     */
    protected function addQuantityOptionsParameter(array $quantityOptions): void
    {
        $this->addParameter(static::PARAMETER_QUANTITY_OPTIONS, $quantityOptions);
    }

    /**
     * @param int $minQuantityInBaseUnit
     *
     * @return void
     */
    protected function addMinQuantityInBaseUnitParameter(int $minQuantityInBaseUnit): void
    {
        $this->addParameter(static::PARAMETER_MIN_QUANTITY_IN_BASE_UNIT, $minQuantityInBaseUnit);
    }

    /**
     * @param float $minQuantityInSalesUnits
     *
     * @return void
     */
    protected function addMinQuantityInSalesUnitsParameter(float $minQuantityInSalesUnits): void
    {
        $this->addParameter(static::PARAMETER_MIN_QUANTITY_IN_SALES_UNITS, $minQuantityInSalesUnits);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null $baseUnit
     *
     * @return void
     */
    protected function addBaseUnitParameter(?ProductMeasurementUnitTransfer $baseUnit = null): void
    {
        $this->addParameter(static::PARAMETER_BASE_UNIT, $baseUnit);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>|null $salesUnits
     *
     * @return void
     */
    protected function addSalesUnitsParameter(?array $salesUnits = null): void
    {
        $this->addParameter(static::PARAMETER_SALES_UNIT, $salesUnits);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>|null $leadProductSalesUnits
     *
     * @return void
     */
    protected function addLeadProductSalesUnitsParameter(?array $leadProductSalesUnits): void
    {
        $this->addParameter(static::PARAMETER_LEAD_PRODUCT_SALES_UNIT, $leadProductSalesUnits);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer|null $productPackagingUnitStorageTransfer
     *
     * @return void
     */
    protected function addProductPackagingUnitParameter(
        ?ProductPackagingUnitStorageTransfer $productPackagingUnitStorageTransfer = null
    ): void {
        $this->addParameter(static::PARAMETER_PRODUCT_PACKAGING_UNIT, $productPackagingUnitStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer|null $productPackagingUnitStorageTransfer
     *
     * @return void
     */
    protected function addPackagingUnitIsSelfLeadParameter(
        ?ProductPackagingUnitStorageTransfer $productPackagingUnitStorageTransfer = null
    ): void {
        $this->addParameter(
            static::PARAMETER_PACKAGING_UNIT_IS_SELF_LEAD,
            $this->isProductPackagingUnitSelfLead($productPackagingUnitStorageTransfer),
        );
    }

    /**
     * @param bool $isAddToCartDisabled
     *
     * @return void
     */
    protected function addIsAddToCartDisabledParameter(bool $isAddToCartDisabled): void
    {
        $this->addParameter(static::PARAMETER_IS_ADD_TO_CART_DISABLED, $isAddToCartDisabled);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null $productQuantityStorageTransfer
     *
     * @return void
     */
    protected function addProductQuantityStorage(
        ?ProductQuantityStorageTransfer $productQuantityStorageTransfer = null
    ): void {
        $this->addParameter(static::PARAMETER_PRODUCT_QUANTITY_STORAGE, $productQuantityStorageTransfer);
    }

    /**
     * @return void
     */
    protected function addNumberFormatConfigParameter(): void
    {
        $numberFormatConfig = $this->getFactory()
            ->getUtilNumberService()
            ->getNumberFormatConfig(
                $this->getFactory()->getLocaleClient()->getCurrentLocale(),
            );

        $this->addParameter(static::PARAMETER_NUMBER_FORMAT_CONFIG, $numberFormatConfig->toArray());
    }
}
