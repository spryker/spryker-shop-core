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
    public const ROUTE_CART_NOTE_QUOTE = 'cart-note/quote';
    public const ROUTE_CART_NOTE_ITEM = 'cart-note/item';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->addQuoteController($allowedLocalesPattern)
            ->addItemController($allowedLocalesPattern);
    }

    /**
     * @param string $allowedLocalesPattern
     *
     * @return $this
     */
    protected function addQuoteController(string $allowedLocalesPattern): self
    {
        $this->createPostController('/{cartNote}/quote', static::ROUTE_CART_NOTE_QUOTE, 'CartNoteWidget', 'Quote', 'index')
            ->assert('cartNote', $allowedLocalesPattern . 'cart-note|cart-note')
            ->value('cartNote', 'cart-note');

        return $this;
    }

    /**
     * @param string $allowedLocalesPattern
     *
     * @return $this
     */
    protected function addItemController(string $allowedLocalesPattern): self
    {
        $this->createPostController('/{cartNote}/item', static::ROUTE_CART_NOTE_ITEM, 'CartNoteWidget', 'Item', 'index')
            ->assert('cartNote', $allowedLocalesPattern . 'cart-note|cart-note')
            ->value('cartNote', 'cart-note');

        return $this;
    }
}
