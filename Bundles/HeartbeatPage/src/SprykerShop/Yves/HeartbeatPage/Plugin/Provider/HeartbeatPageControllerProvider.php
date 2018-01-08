<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HeartbeatPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

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
