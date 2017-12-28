<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\WishlistPage;

use Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface;
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
     * @param Container $container
     *
     * @return Container
     */
    protected function addProductStorageClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new WishlistPageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addWishlistItemExpanderPlugins(Container $container)
    {
        $container[self::PLUGIN_WISHLIST_ITEM_EXPANDERS] = function () {
            return $this->getWishlistItemExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return ProductViewExpanderPluginInterface[]
     */
    protected function getWishlistItemExpanderPlugins()
    {
        return [];
    }
}
