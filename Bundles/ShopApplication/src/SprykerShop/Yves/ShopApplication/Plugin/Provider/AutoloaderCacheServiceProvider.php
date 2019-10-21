<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Kernel\ClassResolver\ResolverCacheManager;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @deprecated Use `\Spryker\Yves\Kernel\Plugin\EventDispatcher\AutoloaderCacheEventDispatcherPlugin` instead.
 */
class AutoloaderCacheServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app->finish(function () {
            $this->persistClassResolverCache();
        });
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @return void
     */
    protected function persistClassResolverCache()
    {
        $resolverCacheManager = new ResolverCacheManager();

        if (!$resolverCacheManager->useCache()) {
            return;
        }

        $cacheProvider = $resolverCacheManager->createClassResolverCacheProvider();
        $cacheProvider->getCache()->persist();
    }
}
