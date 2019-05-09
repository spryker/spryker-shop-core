<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CustomerReorderWidget\Plugin\Router\CustomerReorderWidgetRouteProviderPlugin` instead.
 */
class CustomerReorderControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_CART_ORDER_REPEAT = 'customer/order/reorder';
    protected const ROUTE_CART_ORDER_ITEMS_REPEAT = 'customer/order/reorder-items';
    protected const PATTERN_ID = '\d+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addReorderRoute()
            ->addReorderItemsRoute();
    }

    /**
     * @uses \SprykerShop\Yves\CustomerReorderWidget\Controller\OrderController::reorderAction()
     *
     * @return $this
     */
    protected function addReorderRoute()
    {
        $this->createController('/{customer}/order/{idSalesOrder}/reorder', static::ROUTE_CART_ORDER_REPEAT, 'CustomerReorderWidget', 'Order', 'reorder')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer')
            ->assert('idSalesOrder', static::PATTERN_ID);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CustomerReorderWidget\Controller\OrderController::reorderItemsAction()
     *
     * @return $this
     */
    protected function addReorderItemsRoute()
    {
        $this->createController('/{customer}/order/reorder-items', static::ROUTE_CART_ORDER_ITEMS_REPEAT, 'CustomerReorderWidget', 'Order', 'reorderItems')
            ->assert('customer', $this->getAllowedLocalesPattern() . 'customer|customer')
            ->value('customer', 'customer')
            ->method('POST');

        return $this;
    }
}
