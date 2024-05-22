<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class ConfigurableBundleWidgetAsyncRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_CART_CONFIGURED_BUNDLE_ASYNC_REMOVE = 'cart/configured-bundle/async/remove';

    /**
     * @var string
     */
    public const ROUTE_NAME_CART_CONFIGURED_BUNDLE_ASYNC_CHANGE = 'cart/configured-bundle/async/change';

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
        $routeCollection = $this->addCartConfiguredBundleAsyncRemoveRoute($routeCollection);
        $routeCollection = $this->addCartConfiguredBundleChangeRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundleWidget\Controller\CartAsyncController::removeConfiguredBundleAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartConfiguredBundleAsyncRemoveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/configured-bundle/async/remove/{configuredBundleGroupKey}', 'ConfigurableBundleWidget', 'CartAsync', 'removeConfiguredBundleAction');
        $routeCollection->add(static::ROUTE_NAME_CART_CONFIGURED_BUNDLE_ASYNC_REMOVE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundleWidget\Controller\CartAsyncController::changeConfiguredBundleQuantityAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartConfiguredBundleChangeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/configured-bundle/async/change/{configuredBundleGroupKey}', 'ConfigurableBundleWidget', 'CartAsync', 'changeConfiguredBundleQuantityAction');
        $route = $route->setMethods([Request::METHOD_POST]);
        $routeCollection->add(static::ROUTE_NAME_CART_CONFIGURED_BUNDLE_ASYNC_CHANGE, $route);

        return $routeCollection;
    }
}
