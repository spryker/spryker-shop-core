<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Bootstrap;

use Spryker\Shared\Application\ApplicationInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Dependency\Injector\DependencyInjector;
use Spryker\Yves\Kernel\FactoryResolverAwareTrait;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 */
class YvesBootstrap
{
    use BundleDependencyProviderResolverAwareTrait;
    use FactoryResolverAwareTrait;

    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function boot(): ApplicationInterface
    {
        return $this->getFactory()
            ->createApplication()
            ->boot();
    }

    /**
     * @param \Spryker\Yves\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideExternalDependencies(AbstractBundleDependencyProvider $dependencyProvider, Container $container)
    {
        $dependencyProvider->provideDependencies($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjector $dependencyInjector
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Zed\Kernel\Container
     */
    protected function injectExternalDependencies(DependencyInjector $dependencyInjector, Container $container)
    {
        $container = $dependencyInjector->inject($container);

        return $container;
    }
}
