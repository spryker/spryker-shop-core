<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;

/**
 * Implement this plugin interface to expand form widget parameters.
 */
interface AddToCartFormWidgetParameterExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands form widget parameters.
     *
     * @api
     *
     * @param array<string, mixed> $formParameters
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<string, mixed>
     */
    public function expand(array $formParameters, ProductViewTransfer $productViewTransfer): array;
}
