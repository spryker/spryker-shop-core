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
    public const ROUTE_QUICK_ORDER_SUGGESTION = 'quick-order/suggestion';

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

        $this->createController('/{quickOrder}/suggestion', self::ROUTE_QUICK_ORDER_SUGGESTION, 'QuickOrderPage', 'QuickOrder', 'suggestion')
            ->assert('quickOrder', $allowedLocalesPattern . 'quick-order|quick-order')
            ->value('quickOrder', 'quick-order');
    }
}
