<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class MultiCartPageAsyncRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_MULTI_CART_ASYNC_CLEAR = 'multi-cart-async/clear';

    /**
     * @var string
     */
    protected const PARAM_ID_QUOTE = 'idQuote';

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
        $routeCollection = $this->addMultiCartAsyncClearRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\MultiCartPage\Controller\MultiCartAsyncController::clearAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMultiCartAsyncClearRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/multi-cart-async/clear/{idQuote}', 'MultiCartPage', 'MultiCartAsync', 'clearAction');
        $route = $route->setRequirement(static::PARAM_ID_QUOTE, '\d+');
        $route = $route->setMethods(Request::METHOD_POST);
        $routeCollection->add(static::ROUTE_NAME_MULTI_CART_ASYNC_CLEAR, $route);

        return $routeCollection;
    }
}
