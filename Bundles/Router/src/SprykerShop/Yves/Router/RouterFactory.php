<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\Router;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\Router\Loader\ClosureLoader;
use SprykerShop\Yves\Router\Loader\LoaderInterface;
use SprykerShop\Yves\Router\Resolver\RequestRequestValueResolver;
use SprykerShop\Yves\Router\Resource\ResourceInterface;
use SprykerShop\Yves\Router\Resource\RouterResource;
use SprykerShop\Yves\Router\Route\RouteCollection;
use SprykerShop\Yves\Router\Router\ChainRouter;
use SprykerShop\Yves\Router\Router\Router;
use SprykerShop\Yves\Router\Router\RouterInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\DefaultValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestAttributeValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\SessionValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\VariadicValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface;

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
     * @return \SprykerShop\Yves\Router\Router\RouterInterface
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

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface
     */
    public function createArgumentResolver(): ArgumentResolverInterface
    {
        return new ArgumentResolver(
            $this->createArgumentMetaDataFactory(),
            $this->getArgumentValueResolvers()
        );
    }

    /**
     * @return \Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactoryInterface
     */
    public function createArgumentMetaDataFactory(): ArgumentMetadataFactoryInterface
    {
        return new ArgumentMetadataFactory();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface[]
     */
    public function getArgumentValueResolvers(): array
    {
        return [
            $this->createRequestAttributeValueResolver(),
            $this->createRequestRequestValueResolver(),
            $this->createRequestValueResolver(),
            $this->createSessionValueResolver(),
            $this->createDefaultValueResolver(),
            $this->createVariadicValueResolver(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createRequestAttributeValueResolver(): ArgumentValueResolverInterface
    {
        return new RequestAttributeValueResolver();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createRequestRequestValueResolver(): ArgumentValueResolverInterface
    {
        return new RequestRequestValueResolver();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createRequestValueResolver(): ArgumentValueResolverInterface
    {
        return new RequestValueResolver();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createSessionValueResolver(): ArgumentValueResolverInterface
    {
        return new SessionValueResolver();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createDefaultValueResolver(): ArgumentValueResolverInterface
    {
        return new DefaultValueResolver();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
     */
    public function createVariadicValueResolver(): ArgumentValueResolverInterface
    {
        return new VariadicValueResolver();
    }
}
