<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToGlossaryStorageStorageClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToMessengerClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToSearchClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToStoreClientBridge;

class QuickOrderPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_GLOSSARY = 'CLIENT_GLOSSARY';
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const CLIENT_MESSENGER = 'CLIENT_MESSENGER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCartClient($container);
        $container = $this->addGlossaryClient($container);
        $container = $this->addSearchClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addMessengerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlossaryClient(Container $container): Container
    {
        $container[static::CLIENT_GLOSSARY] = function (Container $container) {
            return new QuickOrderPageToGlossaryStorageStorageClientBridge($container->getLocator()->glossaryStorage()->client());
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
            return new QuickOrderPageToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSearchClient($container): Container
    {
        $container[static::CLIENT_SEARCH] = function (Container $container) {
            return new QuickOrderPageToSearchClientBridge($container->getLocator()->search()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient($container): Container
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new QuickOrderPageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container[static::CLIENT_STORE] = function (Container $container) {
            return new QuickOrderPageToStoreClientBridge($container->getLocator()->store()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new QuickOrderPageToLocaleClientBridge($container->getLocator()->locale()->client());
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
