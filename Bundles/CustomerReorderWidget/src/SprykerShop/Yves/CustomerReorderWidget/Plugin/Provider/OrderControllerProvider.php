<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;

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

        $this->createController('/{cart}/reorder', self::ROUTE_CART_ORDER_REPEAT, 'CartPage', 'Cart', 'reorder')
            ->assert('cart', $allowedLocalesPattern . 'cart|cart')
            ->value('cart', 'cart')
            ->method('POST');
        $this->createController('/{cart}/reorder-items', self::ROUTE_CART_ORDER_ITEMS_REPEAT, 'CartPage', 'Cart', 'reorder')
            ->assert('cart', $allowedLocalesPattern . 'cart|cart')
            ->value('cart', 'cart')
            ->method('POST');
    }
}
