<?php

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
    public const URL_PARAM_ID_PRODUCT_MEASUREMENT_UNIT = 'id-product-measurement-unit';

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
        $idProductMeasurementUnit = $request->request->getInt(static::URL_PARAM_ID_PRODUCT_MEASUREMENT_UNIT);

        $productMeasurementSalesUnitTransfer = new ProductMeasurementSalesUnitTransfer();
        $productMeasurementSalesUnitTransfer->setIdProductMeasurementSalesUnit($idProductMeasurementUnit);
        $itemTransfer->setQuantitySalesUnit($productMeasurementSalesUnitTransfer);
        $itemTransfer->setQuantity($this->calculateQuantity($itemTransfer));

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateQuantity(ItemTransfer $itemTransfer): int
    {
        $quantity = $itemTransfer->getQuantity();

        $quantitySalesUnitTransfer = $itemTransfer->getQuantitySalesUnit();
        $productConcreteMeasurementUnitStorageTransfer = $this->getFactory()
            ->getProductMeasurementUnitStorageClient()
            ->getProductConcreteMeasurementUnit($itemTransfer->getProductConcrete()->getIdProductConcrete());

        if ($productConcreteMeasurementUnitStorageTransfer !== null
            && $productConcreteMeasurementUnitStorageTransfer->getSalesUnits() !== null
        ) {
            foreach ($productConcreteMeasurementUnitStorageTransfer->getSalesUnits() as $salesUnit) {
                if ($salesUnit->getMeasurementUnitId() === $quantitySalesUnitTransfer->getIdProductMeasurementSalesUnit()) {
                    $quantity *= $salesUnit->getConversion();

                    return $quantity;
                }
            }
        }

        return $quantity;
    }
}
