<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface CartItemTransformerPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $cartItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function transformCartItems(array $cartItems, QuoteTransfer $quoteTransfer): array;
}
