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
     *
     * @var string
     */
    protected const ROUTE_RETURN_SLIP_PRINT = 'return/slip-print';

    /**
     * @var string
     */
    public const ROUTE_NAME_RETURN_SLIP_PRINT = 'return/slip-print';

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnViewController::viewAction()
     *
     * @var string
     */
    protected const ROUTE_RETURN_VIEW = 'return/view';

    /**
     * @var string
     */
    public const ROUTE_NAME_RETURN_VIEW = 'return/view';

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnListController::listAction()
     *
     * @var string
     */
    protected const ROUTE_RETURN_LIST = 'return/list';

    /**
     * @var string
     */
    public const ROUTE_NAME_RETURN_LIST = 'return/list';

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnCreateController::createAction()
     *
     * @var string
     */
    protected const ROUTE_RETURN_CREATE = 'return/create';

    /**
     * @var string
     */
    public const ROUTE_NAME_RETURN_CREATE = 'return/create';

    /**
     * @var string
     */
    protected const PARAM_ORDER_REFERENCE = 'orderReference';

    /**
     * @var string
     */
    protected const PARAM_RETURN_REFERENCE = 'returnReference';

    /**
     * @var string
     */
    protected const REFERENCE_REGEX = '[a-zA-Z0-9-]+';

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
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnCreateController::createAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReturnCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/return/create/{orderReference}', 'SalesReturnPage', 'ReturnCreate', 'createAction');
        $route = $route->setRequirement(static::PARAM_ORDER_REFERENCE, static::REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_RETURN_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnListController::listAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReturnListRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/return/list', 'SalesReturnPage', 'ReturnList', 'listAction');
        $routeCollection->add(static::ROUTE_NAME_RETURN_LIST, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnViewController::viewAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReturnViewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/return/view/{returnReference}', 'SalesReturnPage', 'ReturnView', 'viewAction');
        $route = $route->setRequirement(static::PARAM_RETURN_REFERENCE, static::REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_RETURN_VIEW, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnSlipPrintController::printAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addReturnSlipPrintRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/return/slip-print/{returnReference}', 'SalesReturnPage', 'ReturnSlipPrint', 'printAction');
        $route = $route->setRequirement(static::PARAM_RETURN_REFERENCE, static::REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_RETURN_SLIP_PRINT, $route);

        return $routeCollection;
    }
}
