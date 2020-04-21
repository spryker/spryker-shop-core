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
    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnSlipPrintController::printAction()
     */
    protected const ROUTE_RETURN_SLIP_PRINT = 'return/slip-print';
    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnViewController::viewAction()
     */
    protected const ROUTE_RETURN_VIEW = 'return/view';
    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnListController::listAction()
     */
    protected const ROUTE_RETURN_LIST = 'return/list';
    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnCreateController::createAction()
     */
    protected const ROUTE_RETURN_CREATE = 'return/create';

    protected const PARAM_ID_SALES_ORDER = 'idSalesOrder';
    protected const PARAM_ID_SALES_ORDER_REGEX = '[a-zA-Z0-9-]+';

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
        $routeCollection = $this->addReturnCreateRoute($routeCollection);
        $routeCollection = $this->addReturnListRoute($routeCollection);
        $routeCollection = $this->addReturnViewRoute($routeCollection);
        $routeCollection = $this->addReturnSlipPrintRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReturnCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/return/create', 'SalesReturnPage', 'ReturnCreate', 'createAction');
        $routeCollection->add(static::ROUTE_RETURN_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReturnListRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/return/list', 'SalesReturnPage', 'ReturnList', 'listAction');
        $routeCollection->add(static::ROUTE_RETURN_LIST, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReturnViewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/return/view', 'SalesReturnPage', 'ReturnView', 'viewAction');
        $routeCollection->add(static::ROUTE_RETURN_VIEW, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReturnSlipPrintRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/return/slip-print', 'SalesReturnPage', 'ReturnSlipPrint', 'printAction');
        $routeCollection->add(static::ROUTE_RETURN_SLIP_PRINT, $route);

        return $routeCollection;
    }
}
