<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget;

use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToAgentQuoteRequestClientBridge;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToQuoteClientBridge;

class AgentQuoteRequestWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_AGENT_QUOTE_REQUEST = 'CLIENT_AGENT_QUOTE_REQUEST';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addAgentQuoteRequestClient($container);
        $container = $this->addQuoteClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAgentQuoteRequestClient(Container $container): Container
    {
        $container[self::CLIENT_AGENT_QUOTE_REQUEST] = function (Container $container) {
            return new AgentQuoteRequestWidgetToAgentQuoteRequestClientBridge($container->getLocator()->agentQuoteRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container[self::CLIENT_QUOTE] = function (Container $container) {
            return new AgentQuoteRequestWidgetToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }
}
