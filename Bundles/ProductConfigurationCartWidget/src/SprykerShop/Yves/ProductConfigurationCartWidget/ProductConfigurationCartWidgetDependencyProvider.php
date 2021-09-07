<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationCartWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductConfigurationCartWidget\Dependency\Client\ProductConfigurationCartWidgetToProductConfigurationCartClientBridge;

class ProductConfigurationCartWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     * @var string
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_CONFIGURATION_CART = 'CLIENT_PRODUCT_CONFIGURATION_CART';

    /**
     * @var string
     */
    public const PLUGINS_CART_PRODUCT_CONFIGURATION_RENDER_STRATEGY = 'PLUGINS_CART_PRODUCT_CONFIGURATION_RENDER_STRATEGY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductConfigurationCartClient($container);
        $container = $this->addRouter($container);
        $container = $this->addCartProductConfigurationRenderStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductConfigurationCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_CONFIGURATION_CART, function (Container $container) {
            return new ProductConfigurationCartWidgetToProductConfigurationCartClientBridge(
                $container->getLocator()->productConfigurationCart()->client()
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
    protected function addCartProductConfigurationRenderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CART_PRODUCT_CONFIGURATION_RENDER_STRATEGY, function () {
            return $this->getCartProductConfigurationRenderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\ProductConfigurationCartWidgetExtension\Dependency\Plugin\CartProductConfigurationRenderStrategyPluginInterface[]
     */
    protected function getCartProductConfigurationRenderStrategyPlugins(): array
    {
        return [];
    }
}
