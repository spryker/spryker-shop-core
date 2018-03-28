<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class SharedCartPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_SHARED_CART_SHARE = 'shared-cart/share';
    public const ROUTE_SHARED_CART_UN_SHARE = 'shared-cart/un-share';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();
        $controller = $this->createController('/{sharedCart}/share/{idQuote}', static::ROUTE_SHARED_CART_SHARE, 'SharedCartPage', 'Share', 'index');
        $controller->assert('sharedCart', $allowedLocalesPattern . 'shared-cart|shared-cart');
        $controller->value('sharedCart', 'shared-cart');

        $controller = $this->createController('/{sharedCart}/un-share/{idQuote}/{idCompanyUser}/{idPermissionGroup}', static::ROUTE_SHARED_CART_UN_SHARE, 'SharedCartPage', 'UnShare', 'index');
        $controller->assert('sharedCart', $allowedLocalesPattern . 'shared-cart|shared-cart');
        $controller->value('sharedCart', 'shared-cart');
    }
}
