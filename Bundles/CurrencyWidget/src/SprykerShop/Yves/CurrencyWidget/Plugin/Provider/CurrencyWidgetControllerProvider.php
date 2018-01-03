<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CurrencyWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CurrencyWidgetControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_CART = 'currency-switch';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController(
            '/{currency}/switch',
            static::ROUTE_CART,
            'CurrencyWidget',
            'CurrencySwitch',
            'index'
        )->assert('currency', $allowedLocalesPattern . 'currency|currency')
            ->value('currency', 'currency');
    }
}
