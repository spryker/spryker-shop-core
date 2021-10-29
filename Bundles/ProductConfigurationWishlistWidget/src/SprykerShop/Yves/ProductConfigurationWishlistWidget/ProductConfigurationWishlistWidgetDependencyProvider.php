<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationWishlistWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductConfigurationWishlistWidget\Dependency\Client\ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientBridge;

/**
 * @method \SprykerShop\Yves\ProductConfigurationWishlistWidget\ProductConfigurationWishlistWidgetConfig getConfig()
 */
class ProductConfigurationWishlistWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_CONFIGURATION_WISHLIST = 'CLIENT_PRODUCT_CONFIGURATION_WISHLIST';

    /**
     * @var string
     */
    public const PLUGINS_WISHLIST_ITEM_PRODUCT_CONFIGURATION_RENDER_STRATEGY = 'PLUGINS_WISHLIST_ITEM_PRODUCT_CONFIGURATION_RENDER_STRATEGY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductConfigurationWishlistClient($container);
        $container = $this->addWishlistItemProductConfigurationRenderStrategyPlugins($container);
        $container = $this->addRouter($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductConfigurationWishlistClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_CONFIGURATION_WISHLIST, function (Container $container) {
            return new ProductConfigurationWishlistWidgetToProductConfigurationWishlistClientBridge(
                $container->getLocator()->productConfigurationWishlist()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRouter(Container $container): Container
    {
        $container->set(static::SERVICE_ROUTER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_ROUTER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addWishlistItemProductConfigurationRenderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_WISHLIST_ITEM_PRODUCT_CONFIGURATION_RENDER_STRATEGY, function () {
            return $this->getWishlistItemProductConfigurationRenderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\ProductConfigurationWishlistWidgetExtension\Dependency\Plugin\WishlistItemProductConfigurationRenderStrategyPluginInterface>
     */
    protected function getWishlistItemProductConfigurationRenderStrategyPlugins(): array
    {
        return [];
    }
}
