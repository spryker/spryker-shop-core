<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class SalesOrderAmendmentWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_ORDER_AMENDMENT = 'order-amendment';

    /**
     * @var string
     */
    public const ROUTE_NAME_CANCEL_ORDER_AMENDMENT = 'cancel-order-amendment';

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
        $routeCollection = $this->addCancelOrderAmendmentRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\SalesOrderAmendmentWidget\Controller\OrderAmendmentController::amendOrderAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReorderRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/order-amendment/{orderReference}', 'SalesOrderAmendmentWidget', 'OrderAmendment', 'amendOrderAction');
        $route = $route->setRequirement('orderReference', static::PATTERN_REFERENCE_REGEX);
        $route = $route->setMethods([Request::METHOD_POST]);
        $routeCollection->add(static::ROUTE_NAME_ORDER_AMENDMENT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\SalesOrderAmendmentWidget\Controller\CancelOrderAmendmentController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCancelOrderAmendmentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/order-amendment/cancel/{amendedOrderReference}', 'SalesOrderAmendmentWidget', 'CancelOrderAmendment');
        $route = $route->setRequirement('amendedOrderReference', static::PATTERN_REFERENCE_REGEX);
        $route = $route->setMethods([Request::METHOD_POST]);
        $routeCollection->add(static::ROUTE_NAME_CANCEL_ORDER_AMENDMENT, $route);

        return $routeCollection;
    }
}
