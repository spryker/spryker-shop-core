<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\MultiCartWidget;

use Generated\Shared\Transfer\ProductViewTransfer;

interface MultiCartWidgetPluginInterface
{
    const NAME = 'MultiCartWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isButtonDisabled
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, $isButtonDisabled): void;
}
