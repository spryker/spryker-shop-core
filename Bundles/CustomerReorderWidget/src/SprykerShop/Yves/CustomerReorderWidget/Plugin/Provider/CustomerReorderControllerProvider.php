<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CustomerReorderControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_CART_ORDER_REPEAT = 'customer/order/reorder';
    public const ROUTE_CART_ORDER_ITEMS_REPEAT = 'customer/order/reorder-items';
    public const PATTERN_ID = '\d+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController(
            '/{customer}/order/{idOrder}/reorder',
            static::ROUTE_CART_ORDER_REPEAT,
            'CustomerReorderWidget',
            'Order',
            'reorder'
        )
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer')
            ->assert('idOrder', static::PATTERN_ID);

        $this->createController('/{customer}/order/reorder-items', static::ROUTE_CART_ORDER_ITEMS_REPEAT, 'CustomerReorderWidget', 'Order', 'reorderItems')
            ->assert('customer', $allowedLocalesPattern . 'customer|customer')
            ->value('customer', 'customer')
            ->method('POST');
    }
}
