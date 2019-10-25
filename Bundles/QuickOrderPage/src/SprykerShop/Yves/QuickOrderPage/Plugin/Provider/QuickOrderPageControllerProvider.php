<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\QuickOrderPage\Plugin\Router\QuickOrderPageRouteProviderPlugin` instead.
 */
class QuickOrderPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_QUICK_ORDER = 'quick-order';
    public const ROUTE_QUICK_ORDER_ADD_ROWS = 'quick-order/add-rows';
    public const ROUTE_QUICK_ORDER_DELETE_ROW = 'quick-order/delete-row';
    public const ROUTE_QUICK_ORDER_CLEAR_ALL_ROWS = 'quick-order/clear-all-rows';
    public const ROUTE_QUICK_ORDER_PRODUCT_ADDITIONAL_DATA = 'quick-order/product-additional-data';
    protected const ROUTE_QUICK_ORDER_DOWNLOAD_TEMPLATE = 'quick-order/download-template';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addQuickOrderRoute()
            ->addQuickOrderAddRowsRoute()
            ->addQuickOrderDeleteRowRoute()
            ->addQuickOrderProductAdditionalDataRoute()
            ->addQuickOrderDownloadTemplateRoute()
            ->addQuickOrderClearAllRowsRoute()
            ->addQuickOrderProductAdditionalDataRoute();
    }

    /**
     * @uses QuickOrderController::indexAction()
     *
     * @return $this
     */
    protected function addQuickOrderRoute()
    {
        $this->createController('/{quickOrder}', static::ROUTE_QUICK_ORDER, 'QuickOrderPage', 'QuickOrder')
            ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        return $this;
    }

    /**
     * @uses QuickOrderController::addRowsAction()
     *
     * @return $this
     */
    protected function addQuickOrderAddRowsRoute()
    {
        $this->createController('/{quickOrder}/add-rows', static::ROUTE_QUICK_ORDER_ADD_ROWS, 'QuickOrderPage', 'QuickOrder', 'addRows')
            ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        return $this;
    }

    /**
     * @uses QuickOrderController::deleteRowAction()
     *
     * @return $this
     */
    protected function addQuickOrderDeleteRowRoute()
    {
        $this->createController('/{quickOrder}/delete-row', static::ROUTE_QUICK_ORDER_DELETE_ROW, 'QuickOrderPage', 'QuickOrder', 'deleteRow')
            ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        return $this;
    }

    /**
     * @uses QuickOrderController::clearAllRowsAction()
     *
     * @return $this
     */
    protected function addQuickOrderClearAllRowsRoute()
    {
        $this->createController('/{quickOrder}/clear-all-rows', static::ROUTE_QUICK_ORDER_CLEAR_ALL_ROWS, 'QuickOrderPage', 'QuickOrder', 'clearAllRows')
            ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        return $this;
    }

    /**
     * @uses QuickOrderController::productAdditionalDataAction()
     *
     * @return $this
     */
    protected function addQuickOrderProductAdditionalDataRoute()
    {
        $this->createController('/{quickOrder}/product-additional-data', static::ROUTE_QUICK_ORDER_PRODUCT_ADDITIONAL_DATA, 'QuickOrderPage', 'QuickOrder', 'productAdditionalData')
            ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        return $this;
    }

    /**
     * @uses QuickOrderController::downloadTemplateAction()
     *
     * @return $this
     */
    protected function addQuickOrderDownloadTemplateRoute()
    {
        $this->createController('/{quickOrder}/download-template', static::ROUTE_QUICK_ORDER_DOWNLOAD_TEMPLATE, 'QuickOrderPage', 'QuickOrder', 'downloadTemplate')
             ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
             ->value('quickOrder', 'quick-order');

        return $this;
    }
}
