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
        $this->addParameter('itemTransfer', $itemTransfer);
        $this->addParameter('quantitySalesUnit', $itemTransfer->getQuantitySalesUnit());
        $this->addParameter('quantitySalesUnitPrecision', $this->getQuantitySalesUnitPrecision($itemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return float|null
     */
    protected function getQuantitySalesUnitPrecision(ItemTransfer $itemTransfer): ?float
    {
        if ($itemTransfer->getQuantitySalesUnit() === null) {
            return null;
        }

        return $itemTransfer->getQuantitySalesUnit()->getValue() / $itemTransfer->getQuantitySalesUnit()->getPrecision();
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
