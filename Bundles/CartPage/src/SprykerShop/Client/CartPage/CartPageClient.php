<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Client\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerShop\Client\CartPage\CartPageFactory getFactory()
 */
class CartPageClient extends AbstractClient implements CartPageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getCartItems(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFactory()
            ->createCartItemReader()
            ->getCartItems($quoteTransfer);
    }
}
