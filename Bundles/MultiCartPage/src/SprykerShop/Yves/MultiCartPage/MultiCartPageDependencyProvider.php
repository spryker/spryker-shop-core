<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToCustomerClientBridge;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientBridge;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToPersistentCartClientBridge;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToQuoteClientBridge;

class MultiCartPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MULTI_CART = 'CLIENT_MULTI_CART';
    public const CLIENT_PERSISTENT_CART = 'CLIENT_PERSISTENT_CART';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return array|\Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addMultiCartClient($container);
        $container = $this->addPersistentCartClient($container);
        $container = $this->addQuoteClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMultiCartClient($container)
    {
        $container[static::CLIENT_MULTI_CART] = function (Container $container) {
            return new MultiCartPageToMultiCartClientBridge($container->getLocator()->multiCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPersistentCartClient($container)
    {
        $container[static::CLIENT_PERSISTENT_CART] = function (Container $container) {
            return new MultiCartPageToPersistentCartClientBridge($container->getLocator()->persistentCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container)
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new MultiCartPageToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container)
    {
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new MultiCartPageToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }
}
