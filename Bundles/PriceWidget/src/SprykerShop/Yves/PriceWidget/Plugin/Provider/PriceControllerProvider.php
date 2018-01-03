<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\PriceWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class PriceControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_PRICE_SWITCH = 'price-mode-switch';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController(
            '/{price}/mode-switch',
            static::ROUTE_PRICE_SWITCH,
            'PriceWidget',
            'PriceModeSwitch',
            'index'
        )->assert('price', $allowedLocalesPattern . 'price|price')
            ->value('price', 'price');
        ;
    }
}
