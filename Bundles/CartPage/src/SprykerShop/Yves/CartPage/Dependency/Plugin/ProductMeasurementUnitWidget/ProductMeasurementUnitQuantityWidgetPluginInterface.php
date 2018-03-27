<?php

namespace SprykerShop\Yves\CartPage\Dependency\Plugin\ProductMeasurementUnitWidget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ProductMeasurementUnitQuantityWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductMeasurementUnitQuantityWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void;
}
