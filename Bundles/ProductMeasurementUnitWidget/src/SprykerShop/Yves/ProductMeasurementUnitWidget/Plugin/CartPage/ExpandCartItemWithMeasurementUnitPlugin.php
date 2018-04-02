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

        if ($idProductMeasurementSalesUnit === 0) {
            return $itemTransfer;
        }

        $productMeasurementSalesUnitTransfer = new ProductMeasurementSalesUnitTransfer();
        $productMeasurementSalesUnitTransfer->setIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit);
        $itemTransfer->setQuantitySalesUnit($productMeasurementSalesUnitTransfer);

        return $itemTransfer;
    }
}
