<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\ProductMeasurementUnitWidget\QuantitySalesUnitWidgetPluginInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Widget\CartProductMeasurementUnitQuantitySelectorWidget;

/**
 * @deprecated Use \SprykerShop\Yves\ProductMeasurementUnitWidget\Widget\CartProductMeasurementUnitQuantitySelectorWidget instead.
 *
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
        $widget = new CartProductMeasurementUnitQuantitySelectorWidget($itemTransfer);

        $this->parameters = $widget->getParameters();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return CartProductMeasurementUnitQuantitySelectorWidget::getTemplate();
    }
}
