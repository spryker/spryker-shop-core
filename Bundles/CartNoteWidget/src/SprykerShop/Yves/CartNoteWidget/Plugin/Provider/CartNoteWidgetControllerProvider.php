<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CartNoteWidget\Plugin\Router\CartNoteWidgetRouteProviderPlugin` instead.
 */
class CartNoteWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_CART_NOTE_QUOTE = 'cart-note/quote';
    public const ROUTE_CART_NOTE_ITEM = 'cart-note/item';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuoteController()
            ->addItemController();
    }

    /**
     * @return $this
     */
    protected function addQuoteController()
    {
        $this->createPostController('/{cartNote}/quote', static::ROUTE_CART_NOTE_QUOTE, 'CartNoteWidget', 'Quote', 'index')
            ->assert('cartNote', $this->getAllowedLocalesPattern() . 'cart-note|cart-note')
            ->value('cartNote', 'cart-note');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addItemController()
    {
        $this->createPostController('/{cartNote}/item', static::ROUTE_CART_NOTE_ITEM, 'CartNoteWidget', 'Item', 'index')
            ->assert('cartNote', $this->getAllowedLocalesPattern() . 'cart-note|cart-note')
            ->value('cartNote', 'cart-note');

        return $this;
    }
}
