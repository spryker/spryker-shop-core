<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

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
    protected const TWIG_FUNCTION_WIDGET = 'widget';

    protected const TWIG_FUNCTION_WIDGET_BLOCK = 'widgetBlock';

    protected const TWIG_FUNCTION_WIDGET_GLOBAL = 'widgetGlobal';

    protected const TWIG_FUNCTION_WIDGET_EXISTS = 'widgetExists';

    protected const TWIG_FUNCTION_WIDGET_GLOBAL_EXISTS = 'widgetGlobalExists';

    /**
     * @return \Twig_SimpleFunction[]
     */
    protected function getFunctions(): array
    {
        $functions = array_merge(parent::getFunctions(), [
            new Twig_SimpleFunction(static::TWIG_FUNCTION_WIDGET, [$this, 'widget'], [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFunction(static::TWIG_FUNCTION_WIDGET_BLOCK, [$this, 'widgetBlock'], [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFunction(static::TWIG_FUNCTION_WIDGET_GLOBAL, [$this, 'widgetGlobal'], [
                'needs_environment' => true,
                'needs_context' => false,
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFunction(static::TWIG_FUNCTION_WIDGET_EXISTS, [$this, 'widgetExists'], [
                'needs_context' => false,
            ]),
            new Twig_SimpleFunction(static::TWIG_FUNCTION_WIDGET_GLOBAL_EXISTS, [$this, 'widgetGlobalExists'], [
                'needs_context' => false,
            ]),
        ]);

        return $functions;
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
            $widgetFactory = $this->getFactory()->createWidgetFactory();
            $widget = $widgetFactory->build($widgetClass, $arguments);

            $twig->addGlobal('_widget', $widget);

            $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
            $widgetContainerRegistry->add($widget);

            $template = $twig->load($widget::getTemplate());
            $result = $template->render();

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
            $widgetFactory = $this->getFactory()->createWidgetFactory();
            $widget = $widgetFactory->build($widgetClass, $arguments);

            $twig->addGlobal('_widget', $widget);

            $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
            $widgetContainerRegistry->add($widget);

            $template = $twig->load($widget::getTemplate());
            $result = $template->renderBlock($block);

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
            $widgetCollection = $this->getFactory()->createWidgetCollection();

            if (!$widgetCollection->hasWidget($widgetName)) {
                return '';
            }

            $widgetClass = $widgetCollection->getWidgetClassName($widgetName);
            $widgetFactory = $this->getFactory()->createWidgetFactory();
            $widget = $widgetFactory->build($widgetClass, $arguments);

            $twig->addGlobal('_widget', $widget);

            $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
            $widgetContainerRegistry->add($widget);

            $template = $twig->load($widget::getTemplate());
            $result = $template->render();

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
        return $this->getFactory()->createWidgetCollection()->hasWidget($name);
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
