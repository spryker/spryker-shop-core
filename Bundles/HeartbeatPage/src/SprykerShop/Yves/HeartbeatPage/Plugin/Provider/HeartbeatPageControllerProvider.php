<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\HeartbeatPage\Plugin\Provider;

use Pyz\Yves\Application\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class HeartbeatPageControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_HEARTBEAT = 'heartbeat';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{heartbeat}', self::ROUTE_HEARTBEAT, 'HeartbeatPage', 'Heartbeat', 'index')
            ->assert('heartbeat', $allowedLocalesPattern . 'heartbeat|heartbeat');
    }
}
