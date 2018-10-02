<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget;

use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistryInterface;
use SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException;
use Throwable;

class WidgetTagService implements WidgetTagServiceInterface
{
    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetContainerRegistryInterface
     */
    protected $widgetContainerRegistry;

    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    protected $globalWidgetCollection;

    /**
     * @var \SprykerShop\Yves\ShopApplication\Twig\Widget\WidgetFactoryInterface
     */
    protected $widgetFactory;

    /**
     * @param \Spryker\Yves\Kernel\Widget\WidgetContainerRegistryInterface $widgetContainerRegistry
     * @param \Spryker\Yves\Kernel\Widget\WidgetContainerInterface $globalWidgetCollection
     * @param \SprykerShop\Yves\ShopApplication\Twig\Widget\WidgetFactoryInterface $widgetFactory
     */
    public function __construct(
        WidgetContainerRegistryInterface $widgetContainerRegistry,
        WidgetContainerInterface $globalWidgetCollection,
        WidgetFactoryInterface $widgetFactory
    ) {
        $this->widgetContainerRegistry = $widgetContainerRegistry;
        $this->globalWidgetCollection = $globalWidgetCollection;
        $this->widgetFactory = $widgetFactory;
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface|string|null $widgetExpression
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface|null
     */
    public function openWidgetContext($widgetExpression, array $arguments = [])
    {
        if ($widgetExpression === null) {
            return null;
        }

        try {
            $widget = $widgetExpression;

            if (!$widget instanceof WidgetContainerInterface) {
                $widget = $this->createWidgetByName($widget, $arguments);
            }

            if ($widget) {
                $this->widgetContainerRegistry->add($widget);
            }

            return $widget;
        } catch (Throwable $e) {
            throw $this->createWidgetRenderException($widgetExpression, $e);
        }
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface $widget
     * @param string|null $templatePath
     *
     * @return string
     */
    public function getTemplatePath($widget, ?string $templatePath = null): string
    {
        if ($templatePath !== null) {
            return $templatePath;
        }

        return $widget->getTemplate();
    }

    /**
     * @return void
     */
    public function closeWidgetContext(): void
    {
        $this->widgetContainerRegistry->removeLastAdded();
    }

    /**
     * @param string $widgetName
     * @param array $arguments
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface|null
     */
    protected function createWidgetByName(string $widgetName, array $arguments)
    {
        $widgetContainer = $this->widgetContainerRegistry->getLastAdded();

        if (!$widgetContainer || !$widgetContainer->hasWidget($widgetName)) {
            $widgetContainer = $this->globalWidgetCollection;
        }

        if (!$widgetContainer->hasWidget($widgetName)) {
            return null;
        }

        $widgetClass = $widgetContainer->getWidgetClassName($widgetName);

        return $this->widgetFactory->build($widgetClass, $arguments);
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface|string $widgetExpression
     * @param \Throwable $e
     *
     * @return \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     */
    protected function createWidgetRenderException($widgetExpression, Throwable $e): WidgetRenderException
    {
        return new WidgetRenderException(sprintf(
            '%s - Something went wrong in widget "%s": %s in %s:%d',
            get_class($e),
            is_object($widgetExpression) ? get_class($widgetExpression) : $widgetExpression,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ), $e->getCode(), $e);
    }
}
