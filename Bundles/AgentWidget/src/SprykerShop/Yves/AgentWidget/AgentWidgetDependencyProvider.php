<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\AgentWidget\Dependency\Client\AgentWidgetToAgentClientBridge;
use SprykerShop\Yves\AgentWidget\Dependency\Client\AgentWidgetToAgentClientInterface;
use SprykerShop\Yves\AgentWidget\Dependency\Client\AgentWidgetToCustomerClientBridge;
use SprykerShop\Yves\AgentWidget\Dependency\Client\AgentWidgetToCustomerClientInterface;

class AgentWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_AGENT = 'CLIENT_AGENT';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addAgentClient($container);
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAgentClient(Container $container): Container
    {
        $container[static::CLIENT_AGENT] = function (Container $container): AgentWidgetToAgentClientInterface {
            return new AgentWidgetToAgentClientBridge(
                $container->getLocator()->agent()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container): AgentWidgetToCustomerClientInterface {
            return new AgentWidgetToCustomerClientBridge(
                $container->getLocator()->customer()->client()
            );
        };

        return $container;
    }
}
