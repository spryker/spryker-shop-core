<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\UrlPage\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPageExtension\Dependency\Plugin\CartItemTransformerPluginInterface;

/**
 * @method \SprykerShop\Yves\UrlPage\UrlPageFactory getFactory()
 */
class UrlCartItemTransformerPlugin extends AbstractPlugin implements CartItemTransformerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sanitizes invalid item URLs in case the URL is not found in the URL storage.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $cartItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function transformCartItems(array $cartItems, QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->createItemSanitizer()
            ->sanitizeInvalidItemUrls($cartItems);
    }
}
