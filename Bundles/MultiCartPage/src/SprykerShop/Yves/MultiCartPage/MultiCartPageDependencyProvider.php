<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToCartClientBridge;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToGlossaryStorageClientBridge;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientBridge;

class MultiCartPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MULTI_CART = 'CLIENT_MULTI_CART';
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';
    public const PLUGINS_CART_DELETE_COMPANY_USERS_LIST_WIDGET = 'PLUGINS_CART_DELETE_COMPANY_USERS_LIST_WIDGET';
    public const PLUGIN_MULTI_CART_LIST_WIDGETS = 'PLUGIN_MULTI_CART_LIST_WIDGETS';

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
        $container = $this->addCartDeleteCompanyUsersListWidgetPlugins($container);
        $container = $this->addMultiCartListWidgetPlugins($container);
        $container = $this->addGlossaryStorageClient($container);

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
    protected function addCartDeleteCompanyUsersListWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CART_DELETE_COMPANY_USERS_LIST_WIDGET] = function (Container $container) {
            return $this->getCartDeleteCompanyUsersListWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getCartDeleteCompanyUsersListWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMultiCartListWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGIN_MULTI_CART_LIST_WIDGETS] = function () {
            return $this->getMultiCartListWidgetPlugins();
        };

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement
     * Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getMultiCartListWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container[static::CLIENT_GLOSSARY_STORAGE] = function (Container $container) {
            return new MultiCartPageToGlossaryStorageClientBridge($container->getLocator()->glossaryStorage()->client());
        };

        return $container;
    }
}
