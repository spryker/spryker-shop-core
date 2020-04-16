<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class SalesReturnPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_SALES_RETURN_CREATE = 'sales-return/create';
    protected const ROUTE_SALES_RETURN_DETAILS = 'sales-return/details}';

    protected const PARAM_ID_SALES_ORDER = 'idSalesOrder';
    protected const PARAM_REFERENCE_REGEX = '[a-zA-Z0-9-]+';
    protected const PARAM_RETURN_REFERENCE = 'returnReference';

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
        $routeCollection = $this->addSalesReturnCreateRoute($routeCollection);
        $routeCollection = $this->addSalesReturnDetailsRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\SalesReturnCreateController::createAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSalesReturnCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/sales-return/create', 'SalesReturnPage', 'SalesReturnCreate', 'createAction');
        $route = $route->setRequirement(static::PARAM_ID_SALES_ORDER, static::PARAM_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_SALES_RETURN_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\SalesReturnDetailsController::detailsAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSalesReturnDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/sales-return/details/{returnReference}', 'SalesReturnPage', 'SalesReturnDetails', 'detailsAction');
        $route = $route->setRequirement(static::PARAM_RETURN_REFERENCE, static::PARAM_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_SALES_RETURN_DETAILS, $route);

        return $routeCollection;
    }
}
