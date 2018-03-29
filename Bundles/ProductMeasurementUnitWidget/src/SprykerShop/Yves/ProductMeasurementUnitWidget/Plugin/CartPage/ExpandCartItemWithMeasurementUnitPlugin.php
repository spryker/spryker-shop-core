<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartItemBeforeAddPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductMeasurementUnitWidget\ProductMeasurementUnitWidgetFactory getFactory()
 */
class ExpandCartItemWithMeasurementUnitPlugin extends AbstractPlugin implements CartItemBeforeAddPluginInterface
{
    public const URL_PARAM_ID_PRODUCT_MEASUREMENT_SALES_UNIT = 'id-product-measurement-sales-unit';
    public const URL_PARAM_ID_PRODUCT_CONCRETE = 'id-product-concrete';
    public const URL_PARAM_SALES_UNIT_VALUE = 'sales-unit-value';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function add(ItemTransfer $itemTransfer, Request $request): ItemTransfer
    {
        return $this->addProductMeasurementSalesUnitTransfer($itemTransfer, $request);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addProductMeasurementSalesUnitTransfer(ItemTransfer $itemTransfer, Request $request): ItemTransfer
    {
        $idProductMeasurementSalesUnit = $request->request->getInt(static::URL_PARAM_ID_PRODUCT_MEASUREMENT_SALES_UNIT);
        $idProductConcrete = $request->request->getInt(static::URL_PARAM_ID_PRODUCT_CONCRETE);
        $salesUnitValue = (float)$request->request->get(static::URL_PARAM_SALES_UNIT_VALUE);

        if ($idProductMeasurementSalesUnit === 0) {
            return $itemTransfer;
        }

        $productMeasurementSalesUnitTransfer = new ProductMeasurementSalesUnitTransfer();
        $productMeasurementSalesUnitTransfer->setIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit);
        $itemTransfer->setQuantitySalesUnit($productMeasurementSalesUnitTransfer);
        $itemTransfer->setQuantity($this->calculateQuantity($itemTransfer, $idProductConcrete, $salesUnitValue));

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $idProductConcrete
     * @param float $salesUnitValue
     *
     * @return int
     */
    protected function calculateQuantity(ItemTransfer $itemTransfer, int $idProductConcrete, float $salesUnitValue): int
    {
        $quantitySalesUnitTransfer = $itemTransfer->getQuantitySalesUnit();
        $productConcreteMeasurementUnitStorageTransfer = $this->getFactory()
            ->getProductMeasurementUnitStorageClient()
            ->findProductConcreteMeasurementUnitStorage($idProductConcrete);

        if ($productConcreteMeasurementUnitStorageTransfer !== null
            && $productConcreteMeasurementUnitStorageTransfer->getSalesUnits() !== null
        ) {
            foreach ($productConcreteMeasurementUnitStorageTransfer->getSalesUnits() as $salesUnit) {
                if ($salesUnit->getIdProductMeasurementSalesUnit() === $quantitySalesUnitTransfer->getIdProductMeasurementSalesUnit()) {
                    $salesUnitValue *= $salesUnit->getConversion();

                    return (int)$salesUnitValue;
                }
            }
        }

        return $salesUnitValue;
    }
}
