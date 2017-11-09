<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ErrorPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class ErrorPageControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_ERROR_404 = 'error/404';
    const ROUTE_ERROR_404_PATH = '/error/404';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createController(self::ROUTE_ERROR_404_PATH, self::ROUTE_ERROR_404, 'ErrorPage', 'Error404');
    }
}
