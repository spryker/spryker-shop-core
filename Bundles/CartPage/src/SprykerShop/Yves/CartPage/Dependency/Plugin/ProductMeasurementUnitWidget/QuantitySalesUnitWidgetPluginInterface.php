<?php

namespace SprykerShop\Yves\CartPage\Dependency\Plugin\ProductMeasurementUnitWidget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface QuantitySalesUnitWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'QuantitySalesUnitWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void;
}
