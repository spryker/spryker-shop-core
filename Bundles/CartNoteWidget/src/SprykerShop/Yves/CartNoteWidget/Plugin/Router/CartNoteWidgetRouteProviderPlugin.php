<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\Router;

use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class CartNoteWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_CART_NOTE_QUOTE = 'cart-note/quote';
    public const ROUTE_CART_NOTE_ITEM = 'cart-note/item';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addQuoteRoute($routeCollection);
        $routeCollection = $this->addItemRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addQuoteRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart-note/quote', 'CartNoteWidget', 'Quote', 'indexAction');
        $routeCollection->add(static::ROUTE_CART_NOTE_QUOTE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addItemRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart-note/item', 'CartNoteWidget', 'Item', 'indexAction');
        $routeCollection->add(static::ROUTE_CART_NOTE_ITEM, $route);

        return $routeCollection;
    }
}
