<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\HomePage\Plugin\Provider;

use Pyz\Yves\Application\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class HomePageControllerProvider extends AbstractYvesControllerProvider
{

    const ROUTE_HOME = 'home';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{root}', self::ROUTE_HOME, 'HomePage', 'Index')
            ->assert('root', $allowedLocalesPattern)
            ->value('root', '');
    }

}
