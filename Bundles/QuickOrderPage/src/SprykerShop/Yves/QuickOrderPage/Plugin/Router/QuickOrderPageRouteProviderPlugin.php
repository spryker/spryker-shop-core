<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class QuickOrderPageRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    public const ROUTE_QUICK_ORDER = 'quick-order';
    public const ROUTE_QUICK_ORDER_ADD_ROWS = 'quick-order/add-rows';
    public const ROUTE_QUICK_ORDER_DELETE_ROW = 'quick-order/delete-row';
    public const ROUTE_QUICK_ORDER_CLEAR_ALL_ROWS = 'quick-order/clear-all-rows';
    public const ROUTE_QUICK_ORDER_PRODUCT_ADDITIONAL_DATA = 'quick-order/product-additional-data';
    protected const ROUTE_QUICK_ORDER_DOWNLOAD_TEMPLATE = 'quick-order/download-template';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
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
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addQuickOrderRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/quick-order', 'QuickOrderPage', 'QuickOrder', 'indexAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER, $route);
        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::addRowsAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addQuickOrderAddRowsRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/quick-order/add-rows', 'QuickOrderPage', 'QuickOrder', 'addRowsAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER_ADD_ROWS, $route);
        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::deleteRowAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addQuickOrderDeleteRowRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/quick-order/delete-row', 'QuickOrderPage', 'QuickOrder', 'deleteRowAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER_DELETE_ROW, $route);
        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::clearAllRowsAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addQuickOrderClearAllRowsRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/quick-order/clear-all-rows', 'QuickOrderPage', 'QuickOrder', 'clearAllRowsAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER_CLEAR_ALL_ROWS, $route);
        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::productAdditionalDataAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addQuickOrderProductAdditionalDataRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/quick-order/product-additional-data', 'QuickOrderPage', 'QuickOrder', 'productAdditionalDataAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER_PRODUCT_ADDITIONAL_DATA, $route);
        return $routeCollection;
    }

    /**
     * @uses QuickOrderController::downloadTemplateAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addQuickOrderDownloadTemplateRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/quick-order/download-template', 'QuickOrderPage', 'QuickOrder', 'downloadTemplateAction');
        $routeCollection->add(static::ROUTE_QUICK_ORDER_DOWNLOAD_TEMPLATE, $route);
        return $routeCollection;
    }
}
