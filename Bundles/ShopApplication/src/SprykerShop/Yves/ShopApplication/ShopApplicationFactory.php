<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopApplication;

use Silex\Provider\TwigServiceProvider;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Widget\WidgetCollection;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;
use Spryker\Yves\Kernel\Widget\WidgetFactory;
use SprykerShop\Yves\ShopApplication\Dependency\Service\ShopApplicationToUtilTextServiceInterface;
use SprykerShop\Yves\ShopApplication\Twig\RoutingHelper;
use SprykerShop\Yves\ShopApplication\Twig\RoutingHelperInterface;
use SprykerShop\Yves\ShopApplication\Twig\TwigRenderer;

class ShopApplicationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerRegistry
     */
    public function createWidgetContainerRegistry()
    {
        return new WidgetContainerRegistry();
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface
     */
    public function createWidgetFactory()
    {
        return new WidgetFactory();
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
    protected function getApplication()
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
     * @return WidgetCollection
     */
    public function createWidgetCollection()
    {
        return new WidgetCollection($this->getGlobalWidgetPlugins());
    }

    /**
     * @return string[]
     */
    public function getGlobalWidgetPlugins(): array
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::PLUGIN_GLOBAL_WIDGETS);
    }

    /**
     * @return RoutingHelperInterface
     */
    protected function createRoutingHelper()
    {
        return new RoutingHelper($this->getApplication(), $this->getStore(), $this->getUtilTextService());
    }

    /**
     * @return Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::STORE);
    }

    /**
     * @return ShopApplicationToUtilTextServiceInterface
     */
    protected function getUtilTextService(): ShopApplicationToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
