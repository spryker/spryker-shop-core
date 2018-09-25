<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductMeasurementUnitWidget\ProductMeasurementUnitWidgetFactory getFactory()
 */
class CartProductMeasurementUnitQuantitySelectorWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer)
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

        if ($quantitySalesUnitTransfer === null) {
            return true;
        }

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

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'CartProductMeasurementUnitQuantitySelectorWidget';
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductMeasurementUnitWidget/views/cart-product-measurement-unit-quantity-selector/cart-product-measurement-unit-quantity-selector.twig';
    }
}
