<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToAgentQuoteRequestClientBridge;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientBridge;

class AgentQuoteRequestPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUOTE_REQUEST = 'CLIENT_QUOTE_REQUEST';
    public const CLIENT_AGENT_QUOTE_REQUEST = 'CLIENT_AGENT_QUOTE_REQUEST';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addQuoteRequestClient($container);
        $container = $this->addAgentQuoteRequestClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteRequestClient(Container $container): Container
    {
        $container[static::CLIENT_QUOTE_REQUEST] = function (Container $container) {
            return new AgentQuoteRequestPageToQuoteRequestClientBridge($container->getLocator()->quoteRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAgentQuoteRequestClient(Container $container): Container
    {
        $container[static::CLIENT_AGENT_QUOTE_REQUEST] = function (Container $container) {
            return new AgentQuoteRequestPageToAgentQuoteRequestClientBridge($container->getLocator()->agentQuoteRequest()->client());
        };

        return $container;
    }
}
