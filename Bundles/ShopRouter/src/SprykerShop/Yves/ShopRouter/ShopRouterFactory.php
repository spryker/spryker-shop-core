<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopRouter;

use Spryker\Shared\Application\Business\Routing\SilexRouter;
use SprykerShop\Yves\ShopRouter\Creator\ResourceCreatorHandler;
use SprykerShop\Yves\ShopRouter\Creator\ResourceCreatorHandlerInterface;
use SprykerShop\Yves\ShopRouter\Dependency\Plugin\ResourceCreatorPluginInterface;
use SprykerShop\Yves\ShopRouter\Generator\UrlGenerator;
use SprykerShop\Yves\ShopRouter\Merger\ParameterMerger;
use SprykerShop\Yves\ShopRouter\Mapper\UrlMapper;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class ShopRouterFactory extends AbstractFactory
{

    /**
     * @return ResourceCreatorPluginInterface[]
     */
    public function getResourceCreatorPlugins()
    {
        return $this->getProvidedDependency(ShopRouterDependencyProvider::PLUGIN_RESOURCE_CREATORS);
    }

    /**
     * @return \SprykerShop\Yves\ShopRouter\Mapper\UrlMapperInterface
     */
    public function createUrlMapper()
    {
        return new UrlMapper();
    }

    /**
     * @return \SprykerShop\Yves\ShopRouter\Merger\ParameterMergerInterface
     */
    public function createParameterMerger()
    {
        return new ParameterMerger();
    }

    /**
     * @return \Spryker\Client\Collector\Matcher\UrlMatcherInterface
     */
    public function getUrlMatcher()
    {
        return $this->getProvidedDependency(ShopRouterDependencyProvider::CLIENT_COLLECTOR);
    }

    /**
     * @return \Silex\Application
     */
    public function getApplication()
    {
        return $this->getProvidedDependency(ShopRouterDependencyProvider::PLUGIN_APPLICATION);
    }



    /**
     * @return RouterInterface
     */
    public function createSharedSilexRouter()
    {
        return new SilexRouter($this->getApplication());
    }

    /**
     * @param RequestContext $requestContext
     *
     * @return UrlGenerator
     */
    public function createUrlGenerator(RouteCollection $routeCollection ,RequestContext $requestContext)
    {
        return new UrlGenerator($this->getApplication(), $routeCollection, $requestContext);
    }

    /**
     * @return ResourceCreatorHandlerInterface
     */
    public function createResourceCreatorHandler()
    {
        return new ResourceCreatorHandler($this->getResourceCreatorPlugins(), $this->getApplication());
    }
}
