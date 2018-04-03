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

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{quickOrder}', self::ROUTE_QUICK_ORDER, 'QuickOrderPage', 'QuickOrder')
            ->assert('quickOrder', $allowedLocalesPattern . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        $this->createController('/{quickOrder}/add-rows', self::ROUTE_QUICK_ORDER_ADD_ROWS, 'QuickOrderPage', 'QuickOrder', 'addRows')
            ->assert('quickOrder', $allowedLocalesPattern . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');

        $this->createController('/{quickOrder}/delete-row', self::ROUTE_QUICK_ORDER_DELETE_ROW, 'QuickOrderPage', 'QuickOrder', 'deleteRow')
            ->assert('quickOrder', $allowedLocalesPattern . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');
    }
}
