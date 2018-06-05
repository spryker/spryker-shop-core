<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\ProductMeasurementUnitWidget\QuantitySalesUnitWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductMeasurementUnitWidget\ProductMeasurementUnitWidgetFactory getFactory()
 */
class QuantitySalesUnitWidgetPlugin extends AbstractWidgetPlugin implements QuantitySalesUnitWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void
    {
        $this
            ->addParameter('itemTransfer', $itemTransfer)
            ->addParameter('isBaseUnit', $this->isBaseUnit($itemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isBaseUnit(ItemTransfer $itemTransfer): bool
    {
        $quantitySalesUnitTransfer = $itemTransfer->getQuantitySalesUnit();

        if ($quantitySalesUnitTransfer !== null) {
            $productConcreteMeasurementUnitStorageTransfer = $this->getFactory()
                ->getProductMeasurementUnitStorageClient()
                ->findProductConcreteMeasurementUnitStorage($itemTransfer->getId());

            if ($productConcreteMeasurementUnitStorageTransfer !== null) {
                $baseUnitTransfer = $productConcreteMeasurementUnitStorageTransfer->getBaseUnit();

                if ($baseUnitTransfer->getIdProductMeasurementUnit() === $quantitySalesUnitTransfer->getProductMeasurementUnit()->getIdProductMeasurementUnit()) {
                    return true;
                }
            }

            return false;
        }

        return true;
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
        return '@ProductMeasurementUnitWidget/views/cart-product-measurement-unit-quantity-selector/cart-product-measurement-unit-quantity-selector.twig';
    }
}
