<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CurrencyWidget\Plugin\Router\CurrencyWidgetRouteProviderPlugin` instead.
 */
class CurrencyWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_CART = 'currency-switch';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addCurrencySwitchRoute();
    }

    /**
     * @return $this
     */
    protected function addCurrencySwitchRoute()
    {
        $this->createController('/{currency}/switch', static::ROUTE_CART, 'CurrencyWidget', 'CurrencySwitch', 'index')
            ->assert('currency', $this->getAllowedLocalesPattern() . 'currency|currency')
            ->value('currency', 'currency');

        return $this;
    }
}
