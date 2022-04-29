<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;

interface PreAddToCartPluginInterface
{
    /**
     * Specification:
     * - Executed before adding item to cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function preAddToCart(ItemTransfer $itemTransfer, array $params): ItemTransfer;
}
