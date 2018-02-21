<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNotesWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CartNotesWidgetControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_CART_NOTES_QUOTE = 'cart-notes/quote';
    const ROUTE_CART_NOTES_ITEM = 'cart-notes/item';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createGetController(
            '/cart-notes/quote',
            static::ROUTE_CART_NOTES_QUOTE,
            'CartNotesWidget',
            'Quote',
            'index'
        )
            ->method('POST');
        $this->createGetController(
            '/cart-notes/item',
            static::ROUTE_CART_NOTES_ITEM,
            'CartNotesWidget',
            'Item',
            'index'
        )
            ->method('POST');
    }
}
