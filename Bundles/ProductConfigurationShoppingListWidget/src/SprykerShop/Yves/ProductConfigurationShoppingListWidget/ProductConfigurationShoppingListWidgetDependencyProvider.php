<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfigurationShoppingListWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductConfigurationShoppingListWidget\Dependency\Client\ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientBridge;

/**
 * @method \SprykerShop\Yves\ProductConfigurationShoppingListWidget\ProductConfigurationShoppingListWidgetConfig getConfig()
 */
class ProductConfigurationShoppingListWidgetDependencyProvider extends AbstractBundleDependencyProvider
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
    public const CLIENT_PRODUCT_CONFIGURATION_SHOPPING_LIST = 'CLIENT_PRODUCT_CONFIGURATION_SHOPPING_LIST';

    /**
     * @var string
     */
    public const PLUGINS_SHOPPING_LIST_ITEM_PRODUCT_CONFIGURATION_RENDER_STRATEGY = 'PLUGINS_SHOPPING_LIST_ITEM_PRODUCT_CONFIGURATION_RENDER_STRATEGY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductConfigurationShoppingListClient($container);
        $container = $this->addShoppingListItemProductConfigurationRenderStrategyPlugins($container);
        $container = $this->addRouter($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductConfigurationShoppingListClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_CONFIGURATION_SHOPPING_LIST, function (Container $container) {
            return new ProductConfigurationShoppingListWidgetToProductConfigurationShoppingListClientBridge(
                $container->getLocator()->productConfigurationShoppingList()->client(),
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
    protected function addShoppingListItemProductConfigurationRenderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SHOPPING_LIST_ITEM_PRODUCT_CONFIGURATION_RENDER_STRATEGY, function () {
            return $this->getShoppingListItemProductConfigurationRenderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\ProductConfigurationShoppingListWidgetExtension\Dependency\Plugin\ShoppingListItemProductConfigurationRenderStrategyPluginInterface>
     */
    protected function getShoppingListItemProductConfigurationRenderStrategyPlugins(): array
    {
        return [];
    }
}
