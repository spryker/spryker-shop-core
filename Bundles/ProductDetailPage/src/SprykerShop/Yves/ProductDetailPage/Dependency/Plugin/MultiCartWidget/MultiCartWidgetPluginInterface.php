<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\MultiCartWidget;

use Generated\Shared\Transfer\ProductViewTransfer;

/**
 * @deprecated Use \SprykerShop\Yves\MultiCartWidget\Widget\AddToMultiCartWidget instead.
 */
interface MultiCartWidgetPluginInterface
{
    public const NAME = 'MultiCartWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $disabled
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, $disabled): void;
}
