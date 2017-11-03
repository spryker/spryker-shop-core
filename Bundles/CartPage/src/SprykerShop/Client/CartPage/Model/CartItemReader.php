<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Client\CartPage\Model;

use Generated\Shared\Transfer\QuoteTransfer;

class CartItemReader implements CartItemReaderInterface
{
    /**
     * @var \SprykerShop\Client\CartPage\Dependency\Plugin\CartItemTransformerPluginInterface[]
     */
    protected $cartItemTransformerPlugins;

    /**
     * @param \SprykerShop\Client\CartPage\Dependency\Plugin\CartItemTransformerPluginInterface[] $cartItemTransformerPlugins
     */
    public function __construct(array $cartItemTransformerPlugins)
    {
        $this->cartItemTransformerPlugins = $cartItemTransformerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getCartItems(QuoteTransfer $quoteTransfer): array
    {
        $cartItems = $quoteTransfer->getItems()->getArrayCopy();

        foreach ($this->cartItemTransformerPlugins as $cartItemTransformerPlugin) {
            $cartItems = $cartItemTransformerPlugin->transformCartItems($cartItems, $quoteTransfer);
        }

        return $cartItems;
    }
}
