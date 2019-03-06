<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToCartClientBridge;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientBridge;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToQuoteClientBridge;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToQuoteClientInterface;

class MultiCartPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MULTI_CART = 'CLIENT_MULTI_CART';
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return array|\Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addMultiCartClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addQuoteClient($container);

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
            return new MultiCartPageToMultiCartClientBridge($container->getLocator()->multiCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient($container): Container
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new MultiCartPageToCartClientBridge($container->getLocator()->cart()->client());
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
        $container[static::CLIENT_QUOTE] = function (Container $container): MultiCartPageToQuoteClientInterface {
            return new MultiCartPageToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }
}
