<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
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
        $this->addParameter('itemTransfer', $itemTransfer)
            ->addParameter('isBaseUnit', $this->isBaseUnit($itemTransfer))
            ->addParameter('hasSalesUnit', $this->hasSalesUnit($itemTransfer));
        $this->setQuantityRestrictions($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function setQuantityRestrictions(ItemTransfer $itemTransfer): void
    {
        $productQuantityStorageTransfer = $this->getProductQuantityStorageTransfer($itemTransfer);
        if ($productQuantityStorageTransfer === null) {
            $this->addParameter('minQuantity', 1)
                ->addParameter('maxQuantity', null)
                ->addParameter('quantityInterval', 1);

            return;
        }

        $minQuantity = $productQuantityStorageTransfer->getQuantityMin() ?? 1;
        $maxQuantity = $productQuantityStorageTransfer->getQuantityMax();
        $quantityInterval = $productQuantityStorageTransfer->getQuantityInterval() ?? 1;

        $this->addParameter('minQuantity', $minQuantity)
            ->addParameter('maxQuantity', $maxQuantity)
            ->addParameter('quantityInterval', $quantityInterval);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null
     */
    protected function getProductQuantityStorageTransfer(ItemTransfer $itemTransfer): ?ProductQuantityStorageTransfer
    {
        return $this->getFactory()
            ->getProductQuantityStorageClient()
            ->findProductQuantityStorage($itemTransfer->getId());
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasSalesUnit(ItemTransfer $itemTransfer): bool
    {
        $quantitySalesUnitTransfer = $itemTransfer->getQuantitySalesUnit();

        if ($quantitySalesUnitTransfer === null) {
            return false;
        }

        return (bool)$quantitySalesUnitTransfer->getIdProductMeasurementSalesUnit();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartProductMeasurementUnitQuantitySelectorWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductMeasurementUnitWidget/views/cart-product-measurement-unit-quantity-selector/cart-product-measurement-unit-quantity-selector.twig';
    }
}
