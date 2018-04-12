<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartToShoppingListWidget\Plugin\CartPage;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CartPage\Dependency\Plugin\CartToShoppingListWidget\CartToShoppingListWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\CartToShoppingListWidget\CartToShoppingListWidgetFactory getFactory()
 */
class CartToShoppingListWidgetPlugin extends AbstractWidgetPlugin implements CartToShoppingListWidgetPluginInterface
{
    /**
     * @param int $idQuote
     *
     * @return void
     */
    public function initialize(int $idQuote): void
    {
        $this->addParameter('form', $this->getFactory()->getCartFromShoppingListForm($idQuote)->createView());
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
        return '@CartToShoppingListWidget/views/create-shopping-list-from-cart/create-shopping-list-from-cart.twig';
    }
}
