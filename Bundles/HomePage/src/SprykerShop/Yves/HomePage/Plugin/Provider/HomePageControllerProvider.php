<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HomePage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\HomePage\Plugin\Router\HomePageRouteProviderPlugin` instead.
 */
class HomePageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_HOME = 'home';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addHomeRoute();
    }

    /**
     * @return $this
     */
    protected function addHomeRoute()
    {
        $this->createController('/{root}', self::ROUTE_HOME, 'HomePage', 'Index')
            ->assert('root', $this->getAllowedLocalesPattern())
            ->value('root', '');

        return $this;
    }
}
