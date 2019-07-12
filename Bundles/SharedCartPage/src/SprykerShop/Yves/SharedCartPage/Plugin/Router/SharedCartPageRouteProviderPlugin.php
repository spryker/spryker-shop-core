<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class SharedCartPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_SHARED_CART_SHARE = 'shared-cart/share';
    protected const ROUTE_SHARED_CART_DISMISS = 'shared-cart/dismiss';
    protected const ROUTE_SHARED_CART_DISMISS_CONFIRM = 'shared-cart/dismiss-confirm';

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
        $routeCollection = $this->addShareRoute($routeCollection);
        $routeCollection = $this->addDismissRoute($routeCollection);
        $routeCollection = $this->addDismissConfirmRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addShareRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shared-cart/share/{idQuote}', 'SharedCartPage', 'Share', 'indexAction');
        $routeCollection->add(static::ROUTE_SHARED_CART_SHARE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addDismissRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shared-cart/dismiss/{idQuote}', 'SharedCartPage', 'Dismiss', 'indexAction');
        $routeCollection->add(static::ROUTE_SHARED_CART_DISMISS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addDismissConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shared-cart/dismiss-confirm/{idQuote}', 'SharedCartPage', 'Dismiss', 'ConfirmAction');
        $routeCollection->add(static::ROUTE_SHARED_CART_DISMISS_CONFIRM, $route);

        return $routeCollection;
    }
}
