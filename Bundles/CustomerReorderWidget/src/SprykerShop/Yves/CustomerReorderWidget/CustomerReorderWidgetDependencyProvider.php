<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientBridge;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientBridge;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCustomerClientBridge;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToMessengerClientBridge;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientBridge;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientBridge;

class CustomerReorderWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_AVAILABILITY_STORAGE = 'CLIENT_AVAILABILITY_STORAGE';
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_SALES = 'CLIENT_SALES';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_PRODUCT_BUNDLE = 'CLIENT_PRODUCT_BUNDLE';
    public const CLIENT_MESSENGER = 'CLIENT_MESSENGER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addAvailabilityStorageClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addSalesClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addProductBundleClient($container);
        $container = $this->addMessengerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAvailabilityStorageClient(Container $container): Container
    {
        $container[static::CLIENT_AVAILABILITY_STORAGE] = function (Container $container) {
            return new CustomerReorderWidgetToAvailabilityStorageClientBridge(
                $container->getLocator()->availabilityStorage()->client()
            );
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
            return new CustomerReorderWidgetToCartClientBridge(
                $container->getLocator()->cart()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSalesClient(Container $container): Container
    {
        $container[static::CLIENT_SALES] = function (Container $container) {
            return new CustomerReorderWidgetToSalesClientBridge(
                $container->getLocator()->sales()->client()
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
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new CustomerReorderWidgetToCustomerClientBridge(
                $container->getLocator()->customer()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductBundleClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_BUNDLE] = function (Container $container) {
            return new CustomerReorderWidgetToProductBundleClientBridge(
                $container->getLocator()->productBundle()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMessengerClient(Container $container): Container
    {
        $container[static::CLIENT_MESSENGER] = function (Container $container) {
            return new CustomerReorderWidgetToMessengerClientBridge(
                $container->getLocator()->messenger()->client()
            );
        };

        return $container;
    }
}
