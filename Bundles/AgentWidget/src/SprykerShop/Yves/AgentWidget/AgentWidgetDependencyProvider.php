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

class AgentWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_AGENT = 'CLIENT_AGENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addAgentClient($container);

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
}
