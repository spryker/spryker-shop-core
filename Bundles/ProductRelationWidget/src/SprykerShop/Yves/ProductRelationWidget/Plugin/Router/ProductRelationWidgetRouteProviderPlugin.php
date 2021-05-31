<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class ProductRelationWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_NAME_GET_UPSELLING_WIDGET_AJAX = 'cart/get-upselling-widget';

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
        $routeCollection = $this->addGetUpsellingWidgetAjaxRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ProductRelationWidget\Controller\ProductRelationController::getUpsellingProductsWidgetAjaxAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addGetUpsellingWidgetAjaxRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/get-upselling-widget', 'ProductRelationWidget', 'ProductRelation', 'getUpsellingProductsWidgetAjaxAction');
        $route = $route->setMethods(Request::METHOD_GET);
        $routeCollection->add(static::ROUTE_NAME_GET_UPSELLING_WIDGET_AJAX, $route);

        return $routeCollection;
    }
}
