<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CartCodeWidget\Dependency\Client\CartCodeWidgetToCartCodeClientBridge;
use SprykerShop\Yves\CartCodeWidget\Dependency\Client\CartCodeWidgetToQuoteClientBridge;
use SprykerShop\Yves\CartCodeWidget\Dependency\Client\CartCodeWidgetToZedRequestClientBridge;

class CartCodeWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CART_CODE = 'CLIENT_CART_CODE';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCartCodeClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addZedRequestClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartCodeClient(Container $container): Container
    {
        $container[static::CLIENT_CART_CODE] = function (Container $container) {
            return new CartCodeWidgetToCartCodeClientBridge($container->getLocator()->cartCode()->client());
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
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new CartCodeWidgetToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container[static::CLIENT_ZED_REQUEST] = function (Container $container) {
            return new CartCodeWidgetToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        };

        return $container;
    }
}
