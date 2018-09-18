<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Silex\Application;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use SprykerShop\Yves\ShopApplication\Exception\EmptyWidgetRegistryException;
use SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException;
use Throwable;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class WidgetServiceProvider extends WidgetTagServiceProvider
{
    /**
     * @param \Silex\Application $application
     *
     * @return void
     */
    public function register(Application $application)
    {
        parent::register($application);

        $application['twig'] = $application->share(
            $application->extend('twig', function (Twig_Environment $twig) {
                return $this->registerWidgetTwigFunction($twig);
            })
        );
    }

    /**
     * @param \Twig_Environment $twig
     *
     * @return \Twig_Environment
     */
    protected function registerWidgetTwigFunction(Twig_Environment $twig)
    {
        foreach ($this->getFunctions() as $function) {
            $twig->addFunction($function->getName(), $function);
        }

        return $twig;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    protected function getFunctions()
    {
        return [
            new Twig_SimpleFunction('widget', [$this, 'widget'], [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFunction('widgetBlock', [$this, 'widgetBlock'], [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFunction('widgetGlobal', [$this, 'widgetGlobal'], [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFunction('widgetExists', [$this, 'widgetExists'], [
                'needs_context' => false,
            ]),
            new Twig_SimpleFunction('widgetGlobalExists', [$this, 'widgetGlobalExists'], [
                'needs_context' => false,
            ]),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $widgetName
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return string
     */
    public function widget(Twig_Environment $twig, $widgetName, ...$arguments)
    {
        try {
            $widgetContainer = $this->getWidgetContainer();

            if (!$widgetContainer->hasWidget($widgetName)) {
                return '';
            }

            $widgetClass = $widgetContainer->getWidgetClassName($widgetName);
            $widgetFactory = $this->getFactory()->createWidgetPluginFactory();
            $widget = $widgetFactory->build($widgetClass, $arguments);

            $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
            $widgetContainerRegistry->add($widget);

            $template = $twig->load($widget::getTemplate());
            $result = $template->render(['_widget' => $widget]);

            $widgetContainerRegistry->removeLastAdded();

            return $result;
        } catch (Throwable $e) {
            throw $this->createWidgetRenderException($widgetName, $e);
        }
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $widgetName
     * @param string $block
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return string
     */
    public function widgetBlock(Twig_Environment $twig, $widgetName, $block, ...$arguments)
    {
        try {
            $widgetContainer = $this->getWidgetContainer();

            if (!$widgetContainer->hasWidget($widgetName)) {
                return '';
            }

            $widgetClass = $widgetContainer->getWidgetClassName($widgetName);
            $widgetFactory = $this->getFactory()->createWidgetPluginFactory();
            $widget = $widgetFactory->build($widgetClass, $arguments);

            $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
            $widgetContainerRegistry->add($widget);

            $template = $twig->load($widget::getTemplate());
            $result = $template->renderBlock($block, ['_widget' => $widget]);

            $widgetContainerRegistry->removeLastAdded();

            return $result;
        } catch (Throwable $e) {
            throw $this->createWidgetRenderException($widgetName, $e);
        }
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $widgetName
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return string
     */
    public function widgetGlobal(Twig_Environment $twig, $widgetName, ...$arguments)
    {
        try {
            $widgetCollection = $this->getFactory()->getGlobalWidgetCollection();

            if (!$widgetCollection->hasWidget($widgetName)) {
                return '';
            }

            $widgetClass = $widgetCollection->getWidgetClassName($widgetName);
            $widgetFactory = $this->getFactory()->createWidgetPluginFactory();
            $widget = $widgetFactory->build($widgetClass, $arguments);

            $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
            $widgetContainerRegistry->add($widget);

            $template = $twig->load($widget::getTemplate());
            $result = $template->render(['_widget' => $widget]);

            $widgetContainerRegistry->removeLastAdded();

            return $result;
        } catch (Throwable $e) {
            throw $this->createWidgetRenderException($widgetName, $e);
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function widgetExists($name)
    {
        return $this->getWidgetContainer()->hasWidget($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function widgetGlobalExists($name)
    {
        return $this->getFactory()->getGlobalWidgetCollection()->hasWidget($name);
    }

    /**
     * @throws \SprykerShop\Yves\ShopApplication\Exception\EmptyWidgetRegistryException
     *
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    protected function getWidgetContainer(): WidgetContainerInterface
    {
        $widgetRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainer = $widgetRegistry->getLastAdded();

        if (!$widgetContainer) {
            throw new EmptyWidgetRegistryException(sprintf(
                'You have tried to access a widget but %s is empty. To fix this you need to register your widget or view in the registry.',
                get_class($widgetRegistry)
            ));
        }

        return $widgetContainer;
    }

    /**
     * @param string $widgetName
     * @param \Throwable $e
     *
     * @return \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     */
    protected function createWidgetRenderException(string $widgetName, Throwable $e): WidgetRenderException
    {
        return new WidgetRenderException(sprintf(
            '%s - Something went wrong in widget "%s": %s in %s:%d',
            get_class($e),
            $widgetName,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ), $e->getCode(), $e);
    }
}
