<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication;

use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\ShopApplication\Dependency\Client\ShopApplicationToLocaleClientBridge;
use SprykerShop\Yves\ShopApplication\Dependency\Client\ShopApplicationToLocaleClientInterface;
use SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceBridge;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class ShopApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @deprecated Use static::WIDGET_GLOBAL instead.
     *
     * @var string
     */
    public const PLUGIN_GLOBAL_WIDGETS = 'PLUGIN_GLOBAL_WIDGETS';

    /**
     * @var string
     */
    public const WIDGET_GLOBAL = 'WIDGET_GLOBAL';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\ShopApplication\ShopApplicationDependencyProvider::GLOBAL_CONTAINER} instead.
     *
     * @var string
     */
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @var string
     */
    public const GLOBAL_CONTAINER = 'GLOBAL_CONTAINER';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @var string
     */
    public const PLUGINS_FILTER_CONTROLLER_EVENT_SUBSCRIBER = 'PLUGINS_FILTER_CONTROLLER_EVENT_SUBSCRIBER';

    /**
     * @var string
     */
    public const PLUGINS_APPLICATION = 'PLUGINS_APPLICATION';

    /**
     * @var string
     */
    public const PLUGINS_WIDGET_CACHE_KEY_GENERATOR_STRATEGY = 'PLUGINS_WIDGET_CACHE_KEY_GENERATOR_STRATEGY';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addGlobalContainer($container);
        $container = $this->addApplicationPlugin($container);
        $container = $this->addGlobalWidgets($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addFilterControllerEventSubscriberPlugins($container);
        $container = $this->addApplicationPlugins($container);
        $container = $this->addWidgetCacheKeyGeneratorStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlobalContainer(Container $container)
    {
        $container->set(static::GLOBAL_CONTAINER, function () {
            return (new GlobalContainer())->getContainer();
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \SprykerShop\Yves\ShopApplication\ShopApplicationDependencyProvider::addGlobalContainer()} instead.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplicationPlugin(Container $container)
    {
        $container->set(static::PLUGIN_APPLICATION, function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilTextService(Container $container)
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new ShopApplicationToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container): ShopApplicationToLocaleClientInterface {
            return new ShopApplicationToLocaleClientBridge($container->getLocator()->locale()->client());
        });

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
        $container->set(static::WIDGET_GLOBAL, function () {
            return array_unique(array_merge($this->getGlobalWidgets(), $this->getGlobalWidgetPlugins()));
        });

        // Kept for BC reasons
        $container->set(static::PLUGIN_GLOBAL_WIDGETS, function () {
            return $this->getGlobalWidgetPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addWidgetCacheKeyGeneratorStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_WIDGET_CACHE_KEY_GENERATOR_STRATEGY, function () {
            return $this->getWidgetCacheKeyGeneratorStrategyPlugins();
        });

        return $container;
    }

    /**
     * @phpstan-return array<class-string<\Spryker\Yves\Kernel\Widget\AbstractWidget>>
     *
     * @deprecated Use $this->getGlobalWidgets() instead.
     *
     * @return array<string>
     */
    protected function getGlobalWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @phpstan-return array<class-string<\Spryker\Yves\Kernel\Widget\AbstractWidget>>
     *
     * @return array<string>
     */
    protected function getGlobalWidgets(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface>
     */
    protected function getWidgetCacheKeyGeneratorStrategyPlugins(): array
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
        $container->set(static::PLUGINS_FILTER_CONTROLLER_EVENT_SUBSCRIBER, function () {
            return $this->getFilterControllerEventSubscriberPlugins();
        });

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
     * @return array<\Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface>
     */
    protected function getApplicationPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface>
     */
    protected function getFilterControllerEventSubscriberPlugins()
    {
        return [];
    }
}
