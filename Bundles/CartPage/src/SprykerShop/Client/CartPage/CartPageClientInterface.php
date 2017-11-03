<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Client\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \SprykerShop\Client\CartPage\CartPageFactory getFactory()
 */
interface CartPageClientInterface
{
    /**
     * Specification:
     * - TODO: add spec
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getCartItems(QuoteTransfer $quoteTransfer): array;
}
