<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductMeasurementUnitWidget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface ProductMeasurementUnitWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductMeasurementUnitWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $addToCartDisabled
     * @param array $quantityOptions
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, bool $addToCartDisabled, array $quantityOptions = []): void;
}
