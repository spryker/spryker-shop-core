<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication;

use Silex\Provider\TwigServiceProvider;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Widget\WidgetCollection;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;
use Spryker\Yves\Kernel\Widget\WidgetFactory as LegacyWidgetFactory;
use SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceInterface;
use SprykerShop\Yves\ShopApplication\Plugin\ShopApplicationTwigExtensionPlugin;
use SprykerShop\Yves\ShopApplication\Twig\RoutingHelper;
use SprykerShop\Yves\ShopApplication\Twig\TwigRenderer;
use SprykerShop\Yves\ShopApplication\Twig\Widget\TokenParser\WidgetTagTokenParser;
use SprykerShop\Yves\ShopApplication\Twig\Widget\WidgetFactory;
use SprykerShop\Yves\ShopApplication\Twig\Widget\WidgetTagService;
use SprykerShop\Yves\ShopApplication\Twig\Widget\WidgetTagServiceInterface;
use Twig\Extension\ExtensionInterface;
use Twig_TokenParserInterface;

class ShopApplicationFactory extends AbstractFactory
{
    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetContainerInterface|null
     */
    protected static $globalWidgetCollection;

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerRegistryInterface
     */
    public function createWidgetContainerRegistry()
    {
        return new WidgetContainerRegistry();
    }

    /**
     * @deprecated Use createWidgetFactory() method instead.
     *
     * @return \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface
     */
    public function createLegacyWidgetFactory()
    {
        return new LegacyWidgetFactory();
    }

    /**
     * @return \SprykerShop\Yves\ShopApplication\Twig\Widget\WidgetFactoryInterface
     */
    public function createWidgetFactory()
    {
        return new WidgetFactory($this->createLegacyWidgetFactory());
    }

    /**
     * @return \Silex\Provider\TwigServiceProvider
     */
    public function createSilexTwigServiceProvider()
    {
        return new TwigServiceProvider();
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication()
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\ShopApplication\Twig\TwigRendererInterface
     */
    public function createTwigRenderer()
    {
        return new TwigRenderer($this->createRoutingHelper());
    }

    /**
     * @deprecated Use getGlobalWidgetCollection() method instead.
     *
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    public function createWidgetCollection()
    {
        return $this->getGlobalWidgetCollection();
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    public function getGlobalWidgetCollection(): WidgetContainerInterface
    {
        if (static::$globalWidgetCollection === null) {
            static::$globalWidgetCollection = new WidgetCollection($this->getGlobalWidgets());
        }

        return static::$globalWidgetCollection;
    }

    /**
     * @deprecated Use $this->getGlobalWidgets() instead.
     *
     * @return string[]
     */
    public function getGlobalWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::PLUGIN_GLOBAL_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getGlobalWidgets(): array
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::WIDGET_GLOBAL);
    }

    /**
     * @return \SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface[]
     */
    public function getFilterControllerEventSubscriberPlugins(): array
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::PLUGINS_FILTER_CONTROLLER_EVENT_SUBSCRIBER);
    }

    /**
     * @return \SprykerShop\Yves\ShopApplication\Twig\RoutingHelperInterface
     */
    public function createRoutingHelper()
    {
        return new RoutingHelper($this->getApplication(), $this->getStore(), $this->getUtilTextService());
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::STORE);
    }

    /**
     * @return \SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceInterface
     */
    public function getUtilTextService(): ShopApplicationToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Twig_TokenParserInterface
     */
    public function createWidgetTagTokenParser(): Twig_TokenParserInterface
    {
        return new WidgetTagTokenParser();
    }

    /**
     * @return \SprykerShop\Yves\ShopApplication\Twig\Widget\WidgetTagServiceInterface
     */
    public function createWidgetTagService(): WidgetTagServiceInterface
    {
        return new WidgetTagService(
            $this->createWidgetContainerRegistry(),
            $this->getGlobalWidgetCollection(),
            $this->createWidgetFactory()
        );
    }

    /**
     * @return \Twig\Extension\ExtensionInterface|\Spryker\Yves\Twig\Plugin\AbstractTwigExtensionPlugin
     */
    public function createTwigExtensionPlugin(): ExtensionInterface
    {
        return new ShopApplicationTwigExtensionPlugin();
    }
}
