<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceBridge;

class ShopApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @deprecated Use static::WIDGET_GLOBAL instead.
     */
    public const PLUGIN_GLOBAL_WIDGETS = 'PLUGIN_GLOBAL_WIDGETS';
    public const WIDGET_GLOBAL = 'WIDGET_GLOBAL';
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';
    public const STORE = 'STORE';
    public const PLUGINS_FILTER_CONTROLLER_EVENT_SUBSCRIBER = 'PLUGINS_FILTER_CONTROLLER_EVENT_SUBSCRIBER';
    public const PLUGINS_APPLICATION = 'PLUGINS_APPLICATION';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addApplicationPlugin($container);
        $container = $this->addGlobalWidgets($container);
        $container = $this->addStore($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addFilterControllerEventSubscriberPlugins($container);
        $container = $this->addApplicationPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplicationPlugin(Container $container)
    {
        $container[self::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[self::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilTextService(Container $container)
    {
        $container[self::SERVICE_UTIL_TEXT] = function (Container $container) {
            return new ShopApplicationToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        };

        return $container;
    }

    /**
     * @deprecated Use $this->addGlobalWidgets() instead.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlobalWidgetPlugins(Container $container)
    {
        return $this->addGlobalWidgets($container);
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlobalWidgets(Container $container)
    {
        $container[self::WIDGET_GLOBAL] = function () {
            return array_unique(array_merge($this->getGlobalWidgets(), $this->getGlobalWidgetPlugins()));
        };

        // Kept for BC reasons
        $container[self::PLUGIN_GLOBAL_WIDGETS] = function () {
            return $this->getGlobalWidgetPlugins();
        };

        return $container;
    }

    /**
     * @deprecated Use $this->getGlobalWidgets() instead.
     *
     * @return string[]
     */
    protected function getGlobalWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected function getGlobalWidgets(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFilterControllerEventSubscriberPlugins(Container $container): Container
    {
        $container[static::PLUGINS_FILTER_CONTROLLER_EVENT_SUBSCRIBER] = function () {
            return $this->getFilterControllerEventSubscriberPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_APPLICATION, function (Container $container): array {
            return $this->getApplicationPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getApplicationPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface[]
     */
    protected function getFilterControllerEventSubscriberPlugins()
    {
        return [];
    }
}
