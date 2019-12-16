<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use SprykerShop\Yves\ShopApplication\Exception\EmptyWidgetRegistryException;
use SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException;
use Throwable;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class WidgetTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const TWIG_FUNCTION_NAME_WIDGET = 'widget';
    protected const TWIG_FUNCTION_NAME_WIDGET_BLOCK = 'widgetBlock';
    protected const TWIG_FUNCTION_NAME_WIDGET_GLOBAL = 'widgetGlobal';
    protected const TWIG_FUNCTION_NAME_WIDGET_EXISTS = 'widgetExists';
    protected const TWIG_FUNCTION_NAME_WIDGET_GLOBAL_EXISTS = 'widgetGlobalExists';

    protected const TWIG_GLOBAL_VARIABLE_NAME_WIDGET = '_widget';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig = $this->addTwigFunctions($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFunctions(Environment $twig): Environment
    {
        $twig->addFunction($this->createWidgetFunction());
        $twig->addFunction($this->createWidgetBlockFunction());
        $twig->addFunction($this->createWidgetGlobalFunction());
        $twig->addFunction($this->createWidgetExistsFunction());
        $twig->addFunction($this->createWidgetGlobalExistsFunction());

        return $twig;
    }

    /**
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return \Twig\TwigFunction
     */
    protected function createWidgetFunction(): TwigFunction
    {
        return new TwigFunction(
            static::TWIG_FUNCTION_NAME_WIDGET,
            function (Environment $twig, string $widgetName, ...$arguments) {
                try {
                    $widgetContainer = $this->getWidgetContainer();

                    if (!$widgetContainer->hasWidget($widgetName)) {
                        return '';
                    }

                    $widgetClass = $widgetContainer->getWidgetClassName($widgetName);
                    $widgetFactory = $this->getFactory()->createWidgetFactory();
                    $widget = $widgetFactory->build($widgetClass, $arguments);

                    $twig->addGlobal(static::TWIG_GLOBAL_VARIABLE_NAME_WIDGET, $widget);

                    $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
                    $widgetContainerRegistry->add($widget);

                    $template = $twig->load($widget::getTemplate());
                    $result = $template->render();

                    $widgetContainerRegistry->removeLastAdded();

                    return $result;
                } catch (Throwable $e) {
                    throw $this->createWidgetRenderException($widgetName, $e);
                }
            },
            [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]
        );
    }

    /**
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return \Twig\TwigFunction
     */
    protected function createWidgetBlockFunction(): TwigFunction
    {
        return new TwigFunction(
            static::TWIG_FUNCTION_NAME_WIDGET_BLOCK,
            function (Environment $twig, string $widgetName, string $block, ...$arguments) {
                try {
                    $widgetContainer = $this->getWidgetContainer();

                    if (!$widgetContainer->hasWidget($widgetName)) {
                        return '';
                    }

                    $widgetClass = $widgetContainer->getWidgetClassName($widgetName);
                    $widgetFactory = $this->getFactory()->createWidgetFactory();
                    $widget = $widgetFactory->build($widgetClass, $arguments);

                    $twig->addGlobal(static::TWIG_GLOBAL_VARIABLE_NAME_WIDGET, $widget);

                    $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
                    $widgetContainerRegistry->add($widget);

                    $template = $twig->load($widget::getTemplate());
                    $result = $template->renderBlock($block);

                    $widgetContainerRegistry->removeLastAdded();

                    return $result;
                } catch (Throwable $e) {
                    throw $this->createWidgetRenderException($widgetName, $e);
                }
            },
            [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]
        );
    }

    /**
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return \Twig\TwigFunction
     */
    protected function createWidgetGlobalFunction(): TwigFunction
    {
        return new TwigFunction(
            static::TWIG_FUNCTION_NAME_WIDGET_GLOBAL,
            function (Environment $twig, string $widgetName, ...$arguments) {
                try {
                    $widgetCollection = $this->getFactory()->getGlobalWidgetCollection();

                    if (!$widgetCollection->hasWidget($widgetName)) {
                        return '';
                    }

                    $widgetClass = $widgetCollection->getWidgetClassName($widgetName);
                    $widgetFactory = $this->getFactory()->createWidgetFactory();
                    $widget = $widgetFactory->build($widgetClass, $arguments);

                    $twig->addGlobal(static::TWIG_GLOBAL_VARIABLE_NAME_WIDGET, $widget);

                    $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
                    $widgetContainerRegistry->add($widget);

                    $template = $twig->load($widget::getTemplate());
                    $result = $template->render();

                    $widgetContainerRegistry->removeLastAdded();

                    return $result;
                } catch (Throwable $e) {
                    throw $this->createWidgetRenderException($widgetName, $e);
                }
            },
            [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]
        );
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function createWidgetExistsFunction(): TwigFunction
    {
        return new TwigFunction(
            static::TWIG_FUNCTION_NAME_WIDGET_EXISTS,
            function (string $name) {
                return $this->getWidgetContainer()->hasWidget($name);
            },
            [
                'needs_context' => false,
            ]
        );
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function createWidgetGlobalExistsFunction(): TwigFunction
    {
        return new TwigFunction(
            static::TWIG_FUNCTION_NAME_WIDGET_GLOBAL_EXISTS,
            function (string $name) {
                return $this->getFactory()->getGlobalWidgetCollection()->hasWidget($name);
            },
            [
                'needs_context' => false,
            ]
        );
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
