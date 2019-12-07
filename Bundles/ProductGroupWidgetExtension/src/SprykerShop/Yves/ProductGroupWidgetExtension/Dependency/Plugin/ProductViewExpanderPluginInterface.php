<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductViewExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands product view data transfer object with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expand(ProductViewTransfer $productViewTransfer): ProductViewTransfer;
}
