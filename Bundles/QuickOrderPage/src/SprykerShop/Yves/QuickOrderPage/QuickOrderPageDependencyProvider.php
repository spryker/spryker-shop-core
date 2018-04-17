<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToMessengerClientBridge;

class QuickOrderPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_MESSENGER = 'CLIENT_MESSENGER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCartClient($container);
        $container = $this->addMessengerClient($container);

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
            return new QuickOrderPageToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMessengerClient($container): Container
    {
        $container[static::CLIENT_MESSENGER] = function (Container $container) {
            return new QuickOrderPageToMessengerClientBridge($container->getLocator()->messenger()->client());
        };

        return $container;
    }
}
