<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Implement this plugin interface to transform cart items before displaying them on the cart page.
 */
interface CartItemTransformerPluginInterface
{
    /**
     * Specification:
     * - Transforms cart items before displaying them on the cart page.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $cartItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function transformCartItems(array $cartItems, QuoteTransfer $quoteTransfer): array;
}
