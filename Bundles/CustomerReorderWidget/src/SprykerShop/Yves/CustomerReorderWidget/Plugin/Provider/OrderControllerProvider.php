<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class OrderControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_CART_ORDER_REPEAT = 'customer/order/reorder';
    const ROUTE_CART_ORDER_ITEMS_REPEAT = 'customer/order/reorder-items';
//    const SKU_PATTERN = '[a-zA-Z0-9-_]+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{customer}/order/reorder', static::ROUTE_CART_ORDER_REPEAT, 'CustomerReorderWidget', 'Order', 'reorder')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer');
        $this->createController('/{customer}/order/reorder-items', static::ROUTE_CART_ORDER_ITEMS_REPEAT, 'CustomerReorderWidget', 'Order', 'reorderItems')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer')
            ->method('POST');
    }
}
