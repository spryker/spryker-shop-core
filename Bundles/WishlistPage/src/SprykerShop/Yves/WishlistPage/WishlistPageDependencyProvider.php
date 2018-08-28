<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WishlistPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToCustomerClientBridge;
use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToProductStorageClientBridge;
use SprykerShop\Yves\WishlistPage\Dependency\Client\WishlistPageToWishlistClientBridge;

class WishlistPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    const CLIENT_WISHLIST = 'CLIENT_WISHLIST';
    const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    const PLUGIN_WISHLIST_ITEM_EXPANDERS = 'PLUGIN_WISHLIST_ITEM_EXPANDERS';
    public const PLUGIN_WISHLIST_VIEW_WIDGETS = 'PLUGIN_WISHLIST_VIEW_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addWishlistClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addWishlistItemExpanderPlugins($container);
        $container = $this->addWishlistViewWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[self::CLIENT_CUSTOMER] = function (Container $container) {
            return new WishlistPageToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addWishlistClient(Container $container): Container
    {
        $container[self::CLIENT_WISHLIST] = function (Container $container) {
            return new WishlistPageToWishlistClientBridge($container->getLocator()->wishlist()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new WishlistPageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addWishlistItemExpanderPlugins(Container $container)
    {
        $container[self::PLUGIN_WISHLIST_ITEM_EXPANDERS] = function () {
            return $this->getWishlistItemExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addWishlistViewWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGIN_WISHLIST_VIEW_WIDGETS] = function () {
            return $this->getWishlistViewWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected function getWishlistItemExpanderPlugins()
    {
        return [];
    }

    /**
     * Returns a list of widget plugin class names that implement
     * \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    protected function getWishlistViewWidgetPlugins(): array
    {
        return [];
    }
}
