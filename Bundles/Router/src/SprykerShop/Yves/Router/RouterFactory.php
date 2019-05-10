<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\Router;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\Router\Loader\ClosureLoader;
use SprykerShop\Yves\Router\Loader\LoaderInterface;
use SprykerShop\Yves\Router\Resource\ResourceInterface;
use SprykerShop\Yves\Router\Resource\RouterResource;
use SprykerShop\Yves\Router\Route\RouteCollection;
use SprykerShop\Yves\Router\Router\ChainRouter;
use SprykerShop\Yves\Router\Router\Router;
use SprykerShop\Yves\Router\Router\RouterInterface;

/**
 * @method \SprykerShop\Yves\Router\RouterConfig getConfig()
 */
class RouterFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\Router\Router\ChainRouter
     */
    public function createRouter()
    {
        return new ChainRouter($this->getRouterPlugins());
    }

    /**
     * @return \SprykerShop\Yves\RouterExtension\Dependency\Plugin\RouterPluginInterface[]
     */
    protected function getRouterPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_PLUGINS);
    }

    /**
     * @return \SprykerShop\Yves\Router\Router\RouterInterface
     */
    public function createYvesRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createResource(),
            $this->getRouterEnhancerPlugins(),
            $this->getConfig()->getFallbackRouterConfiguration()
        );
    }

    /**
     * @return \SprykerShop\Yves\Router\Loader\LoaderInterface
     */
    protected function createClosureLoader(): LoaderInterface
    {
        return new ClosureLoader();
    }

    /**
     * @return \SprykerShop\Yves\Router\Resource\ResourceInterface
     */
    protected function createResource(): ResourceInterface
    {
        return new RouterResource(
            $this->createRouteCollection(),
            $this->getRouteProviderPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function createRouteCollection(): RouteCollection
    {
        return new RouteCollection(
            $this->getRouteManipulatorPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\RouterExtension\Dependency\Plugin\RouteManipulatorPluginInterface[]
     */
    protected function getRouteManipulatorPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ROUTE_MANIPULATOR);
    }

    /**
     * @return \SprykerShop\Yves\RouterExtension\Dependency\Plugin\RouteProviderPluginInterface[]
     */
    protected function getRouteProviderPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ROUTE_PROVIDER);
    }

    /**
     * @return \SprykerShop\Yves\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[]
     */
    protected function getRouterEnhancerPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_ENHANCER_PLUGINS);
    }

    /**
     * @return RouterInterface
     */
    public function createYvesFallbackRouter(): RouterInterface
    {
        return new Router(
            $this->createClosureLoader(),
            $this->createResource(),
            $this->getRouterEnhancerPlugins(),
            $this->getConfig()->getFallbackRouterConfiguration()
        );
    }
}
