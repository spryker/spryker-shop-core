<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToAgentQuoteRequestClientBridge;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCartClientBridge;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToPersistentCartClientBridge;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToQuoteRequestClientBridge;

class AgentQuoteRequestWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_AGENT_QUOTE_REQUEST = 'CLIENT_AGENT_QUOTE_REQUEST';
    public const CLIENT_QUOTE_REQUEST = 'CLIENT_QUOTE_REQUEST';
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_PERSISTENT_CART = 'CLIENT_PERSISTENT_CART';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addAgentQuoteRequestClient($container);
        $container = $this->addQuoteRequestClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addPersistentCartClient($container);

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
            return new AgentQuoteRequestWidgetToAgentQuoteRequestClientBridge($container->getLocator()->agentQuoteRequest()->client());
        };

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
            return new AgentQuoteRequestWidgetToQuoteRequestClientBridge($container->getLocator()->quoteRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new AgentQuoteRequestWidgetToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPersistentCartClient(Container $container): Container
    {
        $container[static::CLIENT_PERSISTENT_CART] = function (Container $container) {
            return new AgentQuoteRequestWidgetToPersistentCartClientBridge($container->getLocator()->persistentCart()->client());
        };

        return $container;
    }
}
