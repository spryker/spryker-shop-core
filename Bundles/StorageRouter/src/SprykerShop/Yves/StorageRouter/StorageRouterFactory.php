<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StorageRouter;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToStoreClientInterface;
use SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface;
use SprykerShop\Yves\StorageRouter\ParameterMerger\ParameterMerger;
use SprykerShop\Yves\StorageRouter\ParameterMerger\ParameterMergerInterface;
use SprykerShop\Yves\StorageRouter\RequestMatcher\StorageRequestMatcher;
use SprykerShop\Yves\StorageRouter\RouteEnhancer\ControllerRouteEnhancer;
use SprykerShop\Yves\StorageRouter\Router\DynamicRouter;
use SprykerShop\Yves\StorageRouter\UrlGenerator\StorageUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \SprykerShop\Yves\StorageRouter\StorageRouterConfig getConfig()
 */
class StorageRouterFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function createRouter(): RouterInterface
    {
        return new DynamicRouter(
            $this->createRequestMatcher(),
            $this->createUrlGenerator(),
            $this->createRouteEnhancer(),
        );
    }

    /**
     * @return array<\Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface>
     */
    public function createRouteEnhancer(): array
    {
        return [
            new ControllerRouteEnhancer($this->getResourceCreatorPlugins()),
        ];
    }

    /**
     * @return \Symfony\Component\Routing\Matcher\RequestMatcherInterface
     */
    public function createRequestMatcher(): RequestMatcherInterface
    {
        return new StorageRequestMatcher(
            $this->getUrlStorageClient(),
            $this->getStorageRouterEnhancerPlugins(),
        );
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    public function createUrlGenerator(): UrlGeneratorInterface
    {
        return new StorageUrlGenerator(
            $this->getUrlStorageClient(),
            $this->createParameterMerger(),
            $this->getStorageRouterEnhancerPlugins(),
        );
    }

    /**
     * @return \SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface
     */
    public function getUrlStorageClient(): StorageRouterToUrlStorageClientInterface
    {
        return $this->getProvidedDependency(StorageRouterDependencyProvider::CLIENT_URL_STORAGE);
    }

    /**
     * @return array<\SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface>
     */
    public function getResourceCreatorPlugins(): array
    {
        return $this->getProvidedDependency(StorageRouterDependencyProvider::PLUGIN_RESOURCE_CREATORS);
    }

    /**
     * @return \SprykerShop\Yves\StorageRouter\ParameterMerger\ParameterMergerInterface
     */
    public function createParameterMerger(): ParameterMergerInterface
    {
        return new ParameterMerger();
    }

    /**
     * @return array<\SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\StorageRouterEnhancerPluginInterface>
     */
    public function getStorageRouterEnhancerPlugins(): array
    {
        return $this->getProvidedDependency(StorageRouterDependencyProvider::PLUGINS_ROUTER_ENHANCER);
    }

    /**
     * @return \SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToStoreClientInterface
     */
    public function getStoreClient(): StorageRouterToStoreClientInterface
    {
        return $this->getProvidedDependency(StorageRouterDependencyProvider::CLIENT_STORE);
    }
}
