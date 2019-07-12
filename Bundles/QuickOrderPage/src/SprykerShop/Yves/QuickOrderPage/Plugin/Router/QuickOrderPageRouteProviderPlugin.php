<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class QuickOrderPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_QUICK_ORDER = 'quick-order';
    protected const ROUTE_QUICK_ORDER_ADD_ROWS = 'quick-order/add-rows';
    protected const ROUTE_QUICK_ORDER_DELETE_ROW = 'quick-order/delete-row';
    protected const ROUTE_QUICK_ORDER_CLEAR_ALL_ROWS = 'quick-order/clear-all-rows';
    protected const ROUTE_QUICK_ORDER_PRODUCT_ADDITIONAL_DATA = 'quick-order/product-additional-data';
    protected const ROUTE_QUICK_ORDER_DOWNLOAD_TEMPLATE = 'quick-order/download-template';

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
        $routeCollection = $this->addQuickOrderRoute($routeCollection);
        $routeCollection = $this->addQuickOrderAddRowsRoute($routeCollection);
        $routeCollection = $this->addQuickOrderDeleteRowRoute($routeCollection);
        $routeCollection = $this->addQuickOrderProductAdditionalDataRoute($routeCollection);
        $routeCollection = $this->addQuickOrderDownloadTemplateRoute($routeCollection);
        $routeCollection = $this->addQuickOrderClearAllRowsRoute($routeCollection);
        $routeCollection = $this->addQuickOrderProductAdditionalDataRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuickOrderRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quick-order', 'QuickOrderPage', 'QuickOrder', 'indexAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER, $route);

        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::addRowsAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuickOrderAddRowsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quick-order/add-rows', 'QuickOrderPage', 'QuickOrder', 'addRowsAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER_ADD_ROWS, $route);

        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::deleteRowAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuickOrderDeleteRowRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quick-order/delete-row', 'QuickOrderPage', 'QuickOrder', 'deleteRowAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER_DELETE_ROW, $route);

        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::clearAllRowsAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuickOrderClearAllRowsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quick-order/clear-all-rows', 'QuickOrderPage', 'QuickOrder', 'clearAllRowsAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER_CLEAR_ALL_ROWS, $route);

        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::productAdditionalDataAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuickOrderProductAdditionalDataRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quick-order/product-additional-data', 'QuickOrderPage', 'QuickOrder', 'productAdditionalDataAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER_PRODUCT_ADDITIONAL_DATA, $route);

        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::downloadTemplateAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuickOrderDownloadTemplateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quick-order/download-template', 'QuickOrderPage', 'QuickOrder', 'downloadTemplateAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER_DOWNLOAD_TEMPLATE, $route);

        return $routeCollection;
    }
}
