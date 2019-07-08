<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Application;
use SprykerShop\Yves\ResourceSharePage\Activator\ResourceShareActivator;
use SprykerShop\Yves\ResourceSharePage\Activator\ResourceShareActivatorInterface;
use SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToCustomerClientInterface;
use SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToMessengerClientInterface;
use SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientInterface;
use SprykerShop\Yves\ResourceSharePage\RouteResolver\RouteResolver;
use SprykerShop\Yves\ResourceSharePage\RouteResolver\RouteResolverInterface;

class ResourceSharePageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface[]
     */
    public function getResourceShareRouterStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ResourceSharePageDependencyProvider::PLUGINS_RESOURCE_SHARE_ROUTER_STRATEGY);
    }

    /**
     * @return \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToResourceShareClientInterface
     */
    public function getResourceShareClient(): ResourceSharePageToResourceShareClientInterface
    {
        return $this->getProvidedDependency(ResourceSharePageDependencyProvider::CLIENT_RESOURCE_SHARE);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(ResourceSharePageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToCustomerClientInterface
     */
    public function getCustomerClient(): ResourceSharePageToCustomerClientInterface
    {
        return $this->getProvidedDependency(ResourceSharePageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToMessengerClientInterface
     */
    public function getMessengerClient(): ResourceSharePageToMessengerClientInterface
    {
        return $this->getProvidedDependency(ResourceSharePageDependencyProvider::CLIENT_MESSENGER);
    }

    /**
     * @return \SprykerShop\Yves\ResourceSharePage\RouteResolver\RouteResolverInterface
     */
    public function createRouteResolver(): RouteResolverInterface
    {
        return new RouteResolver(
            $this->getMessengerClient(),
            $this->getResourceShareRouterStrategyPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\ResourceSharePage\Activator\ResourceShareActivatorInterface
     */
    public function createResourceShareActivator(): ResourceShareActivatorInterface
    {
        return new ResourceShareActivator(
            $this->getResourceShareClient()
        );
    }
}
