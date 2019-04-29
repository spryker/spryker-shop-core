<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class SharedCartPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_SHARED_CART_SHARE = 'shared-cart/share';
    public const ROUTE_SHARED_CART_DISMISS = 'shared-cart/dismiss';
    public const ROUTE_SHARED_CART_DISMISS_CONFIRM = 'shared-cart/dismiss-confirm';

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addShareRoute($routeCollection);
        $routeCollection = $this->addDismissRoute($routeCollection);
        $routeCollection = $this->addDismissConfirmRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addShareRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shared-cart/share/{idQuote}', 'SharedCartPage', 'Share', 'indexAction');
        $routeCollection->add(static::ROUTE_SHARED_CART_SHARE, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addDismissRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shared-cart/dismiss/{idQuote}', 'SharedCartPage', 'Dismiss', 'indexAction');
        $routeCollection->add(static::ROUTE_SHARED_CART_DISMISS, $route);

        return $routeCollection;
    }

    /**
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addDismissConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/shared-cart/dismiss-confirm/{idQuote}', 'SharedCartPage', 'Dismiss', 'ConfirmAction');
        $routeCollection->add(static::ROUTE_SHARED_CART_DISMISS_CONFIRM, $route);

        return $routeCollection;
    }
}
