<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Plugin\MultiCartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartPage\Dependency\Plugin\CartToShoppingListWidget\CartToShoppingListWidgetPluginInterface;

/**
 * @deprecated Use molecule('cart-to-shopping-list', 'ShoppingListWidget') instead.
 */
class CartToShoppingListWidgetPlugin extends AbstractWidgetPlugin implements CartToShoppingListWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter(
            'quote',
            $quoteTransfer
        );
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/cart-to-shopping-list/cart-to-shopping-list.twig';
    }
}
