<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class YvesSecurityServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    const BCRYPT_FACTOR = 12;

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['security.encoder.digest'] = function ($app) {
            return new BCryptPasswordEncoder(self::BCRYPT_FACTOR);
        };
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
