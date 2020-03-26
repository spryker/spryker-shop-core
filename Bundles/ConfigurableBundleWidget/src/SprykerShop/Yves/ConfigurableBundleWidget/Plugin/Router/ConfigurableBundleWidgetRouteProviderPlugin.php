<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ConfigurableBundleWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_CART_CONFIGURED_BUNDLE_REMOVE = 'cart/configured-bundle/remove';
    protected const ROUTE_CART_CONFIGURED_BUNDLE_CHANGE_QUANTITY = 'cart/configured-bundle/change';

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
        $routeCollection = $this->addCartConfiguredBundleRemoveRoute($routeCollection);
        $routeCollection = $this->addCartConfiguredBundleChangeQuantityRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundleWidget\Controller\CartController::removeConfiguredBundleAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartConfiguredBundleRemoveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/configured-bundle/remove/{configuredBundleGroupKey}', 'ConfigurableBundleWidget', 'Cart', 'removeConfiguredBundleAction');
        $routeCollection->add(static::ROUTE_CART_CONFIGURED_BUNDLE_REMOVE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundleWidget\Controller\CartController::changeConfiguredBundleQuantityAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCartConfiguredBundleChangeQuantityRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/configured-bundle/change/{configuredBundleGroupKey}', 'ConfigurableBundleWidget', 'Cart', 'changeConfiguredBundleQuantityAction');
        $route = $route->setMethods(['POST']);
        $routeCollection->add(static::ROUTE_CART_CONFIGURED_BUNDLE_CHANGE_QUANTITY, $route);

        return $routeCollection;
    }
}
