<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CartNoteWidgetControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_CART_NOTE_QUOTE = 'cart-note/quote';
    const ROUTE_CART_NOTE_ITEM = 'cart-note/item';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createPostController(
            '/cart-note/quote',
            static::ROUTE_CART_NOTE_QUOTE,
            'CartNoteWidget',
            'Quote',
            'index'
        );

        $this->createPostController(
            '/cart-note/item',
            static::ROUTE_CART_NOTE_ITEM,
            'CartNoteWidget',
            'Item',
            'index'
        );
    }
}
