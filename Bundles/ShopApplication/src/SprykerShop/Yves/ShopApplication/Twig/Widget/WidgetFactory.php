<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;
use Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface;
use Spryker\Yves\Kernel\Widget\WidgetFactoryInterface as LegacyWidgetFactoryInterface;
use SprykerShop\Yves\ShopApplication\Exception\InvalidWidgetException;

class WidgetFactory implements WidgetFactoryInterface
{
    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface
     */
    protected $legacyWidgetPluginFactory;

    /**
     * @var \SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorPluginInterface[]
     */
    protected static $widgetCacheKeyGeneratorPlugins = [];

    /**
     * @var array
     */
    protected static $widgetCache = [];

    /**
     * @param \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface $legacyWidgetPluginFactory
     * @param \SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorPluginInterface[] $widgetCacheKeyGeneratorPlugins
     */
    public function __construct(LegacyWidgetFactoryInterface $legacyWidgetPluginFactory, array $widgetCacheKeyGeneratorPlugins = [])
    {
        $this->legacyWidgetPluginFactory = $legacyWidgetPluginFactory;

        if (!static::$widgetCacheKeyGeneratorPlugins) {
            static::$widgetCacheKeyGeneratorPlugins = $this->indexWidgetCacheKeyGeneratorPlugins($widgetCacheKeyGeneratorPlugins);
        }
    }

    /**
     * @param string $widgetClassName
     * @param array $arguments
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|\Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface
     */
    public function build(string $widgetClassName, array $arguments)
    {
        if (is_subclass_of($widgetClassName, WidgetPluginInterface::class)) {
            return $this->legacyWidgetPluginFactory->build($widgetClassName, $arguments);
        }

        $cacheKey = $this->generateCacheKey($widgetClassName, $arguments);

        if ($cacheKey === null) {
            return $this->createWidgetInstance($widgetClassName, $arguments);
        }

        $widget = $this->getCachedWidget($cacheKey);
        if ($widget) {
            return $widget;
        }

        $widget = $this->createWidgetInstance($widgetClassName, $arguments);

        $this->cacheWidget($cacheKey, $widget);

        return $widget;
    }

    /**
     * @param \SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorPluginInterface[] $widgetCacheKeyGeneratorPlugins
     *
     * @return \SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorPluginInterface[]
     */
    protected function indexWidgetCacheKeyGeneratorPlugins(array $widgetCacheKeyGeneratorPlugins): array
    {
        $indexedWidgetCacheKeyGeneratorPlugins = [];

        foreach ($widgetCacheKeyGeneratorPlugins as $widgetCacheKeyGeneratorPlugin) {
            $indexedWidgetCacheKeyGeneratorPlugins[$widgetCacheKeyGeneratorPlugin->getRelatedWidgetClassName()] = $widgetCacheKeyGeneratorPlugin;
        }

        return $indexedWidgetCacheKeyGeneratorPlugins;
    }

    /**
     * @param string $widgetClassName
     * @param array $arguments
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface
     */
    protected function createWidgetInstance(string $widgetClassName, array $arguments): WidgetInterface
    {
        $this->assertClassIsWidget($widgetClassName);

        return new $widgetClassName(...$arguments);
    }

    /**
     * @param string $widgetClassName
     *
     * @throws \SprykerShop\Yves\ShopApplication\Exception\InvalidWidgetException
     *
     * @return void
     */
    protected function assertClassIsWidget(string $widgetClassName): void
    {
        if (!is_subclass_of($widgetClassName, WidgetInterface::class)) {
            throw new InvalidWidgetException(sprintf(
                'Invalid widget %s. This class needs to implement %s.',
                $widgetClassName,
                WidgetInterface::class
            ));
        }
    }

    /**
     * @param string $widgetClassName
     * @param array $arguments
     *
     * @return string|null
     */
    protected function generateCacheKey(string $widgetClassName, array $arguments): ?string
    {
        if (isset(static::$widgetCacheKeyGeneratorPlugins[$widgetClassName])) {
            $key = static::$widgetCacheKeyGeneratorPlugins[$widgetClassName]->generateCacheKey($arguments);

            if ($key === null) {
                return null;
            }

            return md5($widgetClassName . $key);
        }

        return md5($widgetClassName . serialize($arguments));
    }

    /**
     * @param string $cacheKey
     *
     * @return \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface|null
     */
    protected function getCachedWidget(string $cacheKey): ?WidgetInterface
    {
        return static::$widgetCache[$cacheKey] ?? null;
    }

    /**
     * @param string $cacheKey
     * @param \Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface $widget
     *
     * @return void
     */
    protected function cacheWidget(string $cacheKey, WidgetInterface $widget): void
    {
        static::$widgetCache[$cacheKey] = $widget;
    }
}
