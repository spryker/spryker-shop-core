<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class PersistentCartShareWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const CART_CREATE_LINK = 'cart/create-link';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCreateLinkRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\PersistentCartShareWidget\Controller\PersistentCartShareWidgetController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCreateLinkRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/create-link/{idQuote}/{shareOptionGroup}', 'PersistentCartShareWidget', 'PersistentCartShareWidget', 'indexAction');
        $route = $route->setRequirement('idQuote', '\d+');
        $routeCollection->add(static::CART_CREATE_LINK, $route);

        return $routeCollection;
    }
}
