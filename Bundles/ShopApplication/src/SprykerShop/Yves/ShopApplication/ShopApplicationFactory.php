<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopApplication;

use Silex\Provider\TwigServiceProvider;
use Spryker\Yves\Application\ApplicationFactory as SprykerApplicationFactory;
use Spryker\Yves\Application\Plugin\Provider\ExceptionService\SubRequestExceptionHandler;
use Spryker\Yves\Application\Routing\Helper;
use Spryker\Yves\Kernel\Widget\WidgetCollection;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistry;
use Spryker\Yves\Kernel\Widget\WidgetFactory;
use SprykerShop\Yves\ShopApplication\Twig\TwigRenderer;
use Symfony\Component\HttpFoundation\Response;

class ShopApplicationFactory extends SprykerApplicationFactory
{
    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerRegistry
     */
    public function createWidgetContainerRegistry()
    {
        return new WidgetContainerRegistry($this->getApplication());
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface
     */
    public function createWidgetFactory()
    {
        return new WidgetFactory();
    }

    /**
     * @return \Spryker\Yves\Application\Plugin\Provider\ExceptionService\ExceptionHandlerInterface[]
     */
    public function createExceptionHandlers()
    {
        $defaultExceptionHandlers = parent::createExceptionHandlers();

        $exceptionHandlers = [
            Response::HTTP_NOT_FOUND => $this->createSubRequestExceptionHandler(),
        ];

        $exceptionHandlers = ($exceptionHandlers + $defaultExceptionHandlers);

        return $exceptionHandlers;
    }

    /**
     * @return \Silex\Provider\TwigServiceProvider
     */
    public function createSilexTwigServiceProvider()
    {
        return new TwigServiceProvider();
    }

    /**
     * @return \Spryker\Yves\Application\Plugin\Provider\ExceptionService\SubRequestExceptionHandler
     */
    protected function createSubRequestExceptionHandler()
    {
        return new SubRequestExceptionHandler($this->getApplication());
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
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
     * @return \Spryker\Yves\Application\Routing\Helper
     */
    protected function createRoutingHelper()
    {
        return new Helper($this->getApplication());
    }
}
