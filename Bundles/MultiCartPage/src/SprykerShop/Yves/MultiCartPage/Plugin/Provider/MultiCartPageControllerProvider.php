<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class MultiCartPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_MULTI_CART_CREATE = 'multi-cart/create';
    public const ROUTE_MULTI_CART_UPDATE = 'multi-cart/update';
    public const ROUTE_MULTI_CART_DELETE = 'multi-cart/delete';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();
        $controller = $this->createController('/{multiCart}/create', self::ROUTE_MULTI_CART_CREATE, 'MultiCartPage', 'MultiCart', 'create');
        $controller->assert('multiCart', $allowedLocalesPattern . 'multi-cart|multi-cart');
        $controller->value('multiCart', 'multi-cart');

        $controller = $this->createController('/{multiCart}/update/{quoteName}', self::ROUTE_MULTI_CART_UPDATE, 'MultiCartPage', 'MultiCart', 'update');
        $controller->assert('multiCart', $allowedLocalesPattern . 'multi-cart|multi-cart')
            ->assert('quoteName', '.+');
        $controller->value('multiCart', 'multi-cart');

        $controller = $this->createGetController('/{multiCart}/delete/{quoteName}', self::ROUTE_MULTI_CART_DELETE, 'MultiCartPage', 'MultiCart', 'delete');
        $controller->assert('multiCart', $allowedLocalesPattern . 'multi-cart|multi-cart')
            ->assert('quoteName', '.+');
        $controller->value('multiCart', 'multi-cart');
    }
}
