<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\SharedCartWidget\Dependency\Client\SharedCartWidgetToCustomerClientBridge;
use SprykerShop\Yves\SharedCartWidget\Dependency\Client\SharedCartWidgetToMultiCartClientBridge;
use SprykerShop\Yves\SharedCartWidget\Dependency\Client\SharedCartWidgetToQuoteClientBridge;
use SprykerShop\Yves\SharedCartWidget\Dependency\Client\SharedCartWidgetToSharedCartClientBridge;

class SharedCartWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_MULTI_CART = 'CLIENT_MULTI_CART';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const CLIENT_SHARED_CART = 'CLIENT_SHARED_CART';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addMultiCartClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addSharedCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient($container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new SharedCartWidgetToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMultiCartClient($container): Container
    {
        $container[static::CLIENT_MULTI_CART] = function (Container $container) {
            return new SharedCartWidgetToMultiCartClientBridge($container->getLocator()->multiCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient($container): Container
    {
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new SharedCartWidgetToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSharedCartClient($container): Container
    {
        $container[static::CLIENT_SHARED_CART] = function (Container $container) {
            return new SharedCartWidgetToSharedCartClientBridge($container->getLocator()->sharedCart()->client());
        };

        return $container;
    }
}
