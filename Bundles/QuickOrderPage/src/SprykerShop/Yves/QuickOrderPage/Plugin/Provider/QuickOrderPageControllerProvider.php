<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class QuickOrderPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_QUICK_ORDER = 'quick-order';
    public const ROUTE_QUICK_ORDER_ADD_ROWS = 'quick-order/add-rows';
    public const ROUTE_QUICK_ORDER_DELETE_ROW = 'quick-order/delete-row';
    public const ROUTE_QUICK_ORDER_PRODUCT_ADDITIONAL_DATA = 'quick-order/product-additional-data';
    public const ROUTE_QUICK_ORDER_PRODUCT_PRICE = 'quick-order/product-price';

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
            ->addQuickOrderProductPriceRoute();
    }

    /**
     * @return $this
     */
    protected function addQuickOrderRoute(): self
    {
        $this->createController('/{quickOrder}', self::ROUTE_QUICK_ORDER, 'QuickOrderPage', 'QuickOrder')
            ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addQuickOrderAddRowsRoute(): self
    {
        $this->createController('/{quickOrder}/add-rows', self::ROUTE_QUICK_ORDER_ADD_ROWS, 'QuickOrderPage', 'QuickOrder', 'addRows')
            ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addQuickOrderDeleteRowRoute(): self
    {
        $this->createController('/{quickOrder}/delete-row', self::ROUTE_QUICK_ORDER_DELETE_ROW, 'QuickOrderPage', 'QuickOrder', 'deleteRow')
            ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        return $this;
    }

    /**
     * @uses QuickOrderController::productAdditionalDataAction()
     *
     * @return $this
     */
    protected function addQuickOrderProductAdditionalDataRoute(): AbstractYvesControllerProvider
    {
        $this->createController('/{quickOrder}/product-additional-data', static::ROUTE_QUICK_ORDER_PRODUCT_ADDITIONAL_DATA, 'QuickOrderPage', 'QuickOrder', 'productAdditionalData')
            ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addQuickOrderProductPriceRoute(): AbstractYvesControllerProvider
    {
        $this->createController('/{quickOrder}/product-price', static::ROUTE_QUICK_ORDER_PRODUCT_PRICE, 'QuickOrderPage', 'QuickOrder', 'productPrice')
            ->assert('quickOrder', $this->getAllowedLocalesPattern() . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        return $this;
    }
}
