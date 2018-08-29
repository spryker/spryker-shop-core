<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget;

use Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use Spryker\Yves\Kernel\Widget\WidgetContainerRegistryInterface;
use Spryker\Yves\Kernel\Widget\WidgetFactoryInterface;
use SprykerShop\Yves\ShopApplication\Exception\EmptyWidgetRegistryException;
use SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException;
use Throwable;
use Twig_Environment;

class WidgetTagService implements WidgetTagServiceInterface
{
    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetContainerRegistry
     */
    protected $widgetContainerRegistry;

    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface
     */
    protected $widgetFactory;

    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    protected $globalWidgetCollection;

    /**
     * @param \Spryker\Yves\Kernel\Widget\WidgetContainerRegistryInterface $widgetContainerRegistry
     * @param \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface $widgetFactory
     * @param \Spryker\Yves\Kernel\Widget\WidgetContainerInterface $globalWidgetCollection
     */
    public function __construct(
        WidgetContainerRegistryInterface $widgetContainerRegistry,
        WidgetFactoryInterface $widgetFactory,
        WidgetContainerInterface $globalWidgetCollection
    ) {
        $this->widgetContainerRegistry = $widgetContainerRegistry;
        $this->widgetFactory = $widgetFactory;
        $this->globalWidgetCollection = $globalWidgetCollection;
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $widgetName
     * @param array $arguments
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\WidgetRenderException
     *
     * @return bool
     */
    public function openWidgetContext(Twig_Environment $twig, string $widgetName, array $arguments = []): bool
    {
        try {
            $widgetContainer = $this->widgetContainerRegistry->getLastAdded();

            if (!$widgetContainer || !$widgetContainer->hasWidget($widgetName)) {
                $widgetContainer = $this->globalWidgetCollection;
            }

            if (!$widgetContainer->hasWidget($widgetName)) {
                return false;
            }

            $widgetClass = $widgetContainer->getWidgetClassName($widgetName);
            $widget = $this->widgetFactory->build($widgetClass, $arguments);
            $this->widgetContainerRegistry->add($widget);

            return true;
        } catch (Throwable $e) {
            throw $this->createWidgetRenderException($widgetName, $e);
        }
    }

    /**
     * @param null|string $templatePath
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\EmptyWidgetRegistryException
     *
     * @return string
     */
    public function getTemplatePath(?string $templatePath = null): string
    {
        if ($templatePath !== null) {
            return $templatePath;
        }

        $widget = $this->widgetContainerRegistry->getLastAdded();
        if ($widget instanceof WidgetInterface) {
            return $widget->getTemplate();
        }

        throw new EmptyWidgetRegistryException('Widget registry is empty. Make sure to open a widget context before trying to access its template path.');
    }

    /**
     * @return void
     */
    public function closeWidgetContext(): void
    {
        $this->widgetContainerRegistry->removeLastAdded();
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    public function getCurrentWidget(): WidgetContainerInterface
    {
        return $this->widgetContainerRegistry->getLastAdded();
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
