<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CartNoteWidgetRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    public const ROUTE_CART_NOTE_QUOTE = 'cart-note/quote';
    public const ROUTE_CART_NOTE_ITEM = 'cart-note/item';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $this->addQuoteController()
            ->addItemController();
        return $routeCollection;
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
