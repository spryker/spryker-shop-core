<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\Application;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Application as SilexApplication;
use Spryker\Yves\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Yves\Kernel\Plugin\Pimple;

abstract class YvesBootstrap
{
    use BundleDependencyProviderResolverAwareTrait;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @var \SprykerShop\Yves\ShopApplication\ShopApplicationConfig
     */
    protected $config;

    /**
     * @var \Spryker\Shared\Application\Application|null
     */
    protected $sprykerApplication;

    public function __construct()
    {
        $this->application = $this->getBaseApplication();

        if ($this->application instanceof ContainerInterface) {
            $this->sprykerApplication = new Application($this->application);
        }

        $this->config = new ShopApplicationConfig();
    }

    /**
     * @return \Spryker\Shared\Application\Application|\Spryker\Yves\Kernel\Application
     */
    public function boot()
    {
        $this->registerServiceProviders();

        if ($this->sprykerApplication !== null) {
            $this->setupApplication();
        }

        $this->registerRouters();

        $this->registerControllerProviders();

        $this->application->boot();

        if ($this->sprykerApplication === null) {
            return $this->application;
        }

        $this->sprykerApplication->boot();

        return $this->sprykerApplication;
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getBaseApplication(): SilexApplication
    {
        $application = new SilexApplication();
        Pimple::setApplication($application);

        return $application;
    }

    /**
     * @return void
     */
    protected function setupApplication(): void
    {
        foreach ($this->getApplicationPlugins() as $applicationPlugin) {
            $this->sprykerApplication->registerApplicationPlugin($applicationPlugin);
        }
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::PLUGINS_APPLICATION);
    }

    /**
     * @param \Spryker\Yves\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideExternalDependencies(AbstractBundleDependencyProvider $dependencyProvider, Container $container): Container
    {
        $container = $dependencyProvider->provideDependencies($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface $dependencyInjector
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function injectExternalDependencies(DependencyInjectorInterface $dependencyInjector, Container $container): Container
    {
        /**
         * @var \Spryker\Yves\Kernel\Container
         */
        $container = $dependencyInjector->inject($container);

        return $container;
    }

    /**
     * @return void
     */
    abstract protected function registerServiceProviders();

    /**
     * @deprecated Use `\Spryker\Yves\Router\RouterDependencyProvider::getRouterPlugins()` instead.
     *
     * @return void
     */
    protected function registerRouters()
    {
    }

    /**
     * @deprecated Use `\Spryker\Yves\Router\RouterDependencyProvider::getRouteProvider()` instead.
     *
     * @return void
     */
    protected function registerControllerProviders()
    {
    }
}
