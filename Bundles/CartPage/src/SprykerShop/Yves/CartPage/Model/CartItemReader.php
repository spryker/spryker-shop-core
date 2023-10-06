<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Model;

use Generated\Shared\Transfer\QuoteTransfer;

class CartItemReader implements CartItemReaderInterface
{
    /**
     * @var array<\SprykerShop\Yves\CartPageExtension\Dependency\Plugin\CartItemTransformerPluginInterface>
     */
    protected $cartItemTransformerPlugins;

    /**
     * @param array<\SprykerShop\Yves\CartPageExtension\Dependency\Plugin\CartItemTransformerPluginInterface> $cartItemTransformerPlugins
     */
    public function __construct(array $cartItemTransformerPlugins)
    {
        $this->cartItemTransformerPlugins = $cartItemTransformerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
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
