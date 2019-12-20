<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

/**
 * @deprecated Will be removed without replacement. The `\Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder`
 */
class YvesSecurityServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const BCRYPT_FACTOR = 12;

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
