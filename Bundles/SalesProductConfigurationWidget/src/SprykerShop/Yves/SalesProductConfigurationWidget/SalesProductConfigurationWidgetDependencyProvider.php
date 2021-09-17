<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductConfigurationWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\SalesProductConfigurationWidget\Dependency\Client\SalesProductConfigurationWidgetToSalesProductConfigurationClientBridge;

class SalesProductConfigurationWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_SALES_PRODUCT_CONFIGURATION_RENDER_STRATEGY = 'PLUGINS_SALES_PRODUCT_CONFIGURATION_RENDER_STRATEGY';
    /**
     * @var string
     */
    public const CLIENT_SALES_PRODUCT_CONFIGURATION = 'CLIENT_SALES_PRODUCT_CONFIGURATION';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addSalesProductConfigurationRenderStrategyPlugins($container);
        $container = $this->addSalesProductConfigurationClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSalesProductConfigurationRenderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SALES_PRODUCT_CONFIGURATION_RENDER_STRATEGY, function () {
            return $this->getSalesProductConfigurationRenderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSalesProductConfigurationClient(Container $container): Container
    {
        $container->set(static::CLIENT_SALES_PRODUCT_CONFIGURATION, function (Container $container) {
            return new SalesProductConfigurationWidgetToSalesProductConfigurationClientBridge(
                $container->getLocator()->salesProductConfiguration()->client()
            );
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\SalesProductConfigurationWidgetExtension\Dependency\Plugin\SalesProductConfigurationRenderStrategyPluginInterface>
     */
    protected function getSalesProductConfigurationRenderStrategyPlugins(): array
    {
        return [];
    }
}
