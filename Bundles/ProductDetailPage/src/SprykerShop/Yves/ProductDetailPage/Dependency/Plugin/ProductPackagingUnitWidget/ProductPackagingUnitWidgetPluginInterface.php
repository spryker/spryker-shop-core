<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\ProductPackagingUnitWidget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductPackagingUnitWidget\Widget\ProductPackagingUnitWidget instead.
 */
interface ProductPackagingUnitWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductPackagingUnitWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isAddToCartDisabled
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, bool $isAddToCartDisabled): void;
}
