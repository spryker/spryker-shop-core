<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartReorderPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class CartReorderPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_CART_REORDER = 'cart-reorder';

    /**
     * @var string
     */
    protected const PATTERN_REFERENCE_REGEX = '[a-zA-Z0-9-_]+';

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
        $routeCollection = $this->addReorderRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CartReorderPage\Controller\OrderController::reorderAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReorderRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart-reorder/{orderReference}', 'CartReorderPage', 'Reorder', 'reorderAction');
        $route = $route->setRequirement('orderReference', static::PATTERN_REFERENCE_REGEX);
        $route = $route->setMethods([Request::METHOD_POST]);
        $routeCollection->add(static::ROUTE_NAME_CART_REORDER, $route);

        return $routeCollection;
    }
}
