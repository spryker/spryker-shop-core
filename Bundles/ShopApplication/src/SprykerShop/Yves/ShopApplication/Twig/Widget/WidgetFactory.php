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
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorInterface;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetNoCacheFlagPluginInterface;

class WidgetFactory implements WidgetFactoryInterface
{
    /**
     * @var \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface
     */
    protected $legacyWidgetPluginFactory;

    /**
     * @var array
     */
    protected static $widgetCache = [];

    protected static $widgetKeyGeneratorCache = [];

    /**
     * @param \Spryker\Yves\Kernel\Widget\WidgetFactoryInterface $legacyWidgetPluginFactory
     */
    public function __construct(LegacyWidgetFactoryInterface $legacyWidgetPluginFactory)
    {
        $this->legacyWidgetPluginFactory = $legacyWidgetPluginFactory;
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

        if (!$this->widgetCanBeCached($widgetClassName)) {
            return $this->createWidgetInstance($widgetClassName, $arguments);
        }

        $cacheKey = $this->generateCacheKey($widgetClassName, $arguments);
        $widget = $this->getCachedWidget($cacheKey);
        if ($widget) {
            return $widget;
        }

        $widget = $this->createWidgetInstance($widgetClassName, $arguments);

        $this->cacheWidget($cacheKey, $widget);

        return $widget;
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
     * @return bool
     */
    protected function widgetCanBeCached(string $widgetClassName): bool
    {
        return !is_subclass_of($widgetClassName, WidgetNoCacheFlagPluginInterface::class);
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
     * @return string
     */
    protected function generateCacheKey(string $widgetClassName, array $arguments): string
    {
        $cacheKey = $this->generateStandaloneCacheKey($widgetClassName, $arguments);

        if ($cacheKey !== null) {
            return $cacheKey;
        }

        return md5($widgetClassName . serialize($arguments));
    }

    protected function generateStandaloneCacheKey(string $widgetClassName, array $arguments): ?string
    {
        $widgetCacheKeyGeneratorClassName = $widgetClassName . 'CacheKeyGenerator';
        if (!class_exists($widgetCacheKeyGeneratorClassName) || !is_subclass_of($widgetCacheKeyGeneratorClassName, WidgetCacheKeyGeneratorInterface::class)) {
            return null;
        }

        if (!isset(static::$widgetKeyGeneratorCache[$widgetCacheKeyGeneratorClassName])) {
            $keyGenerator = new $widgetCacheKeyGeneratorClassName();
            static::$widgetKeyGeneratorCache[$widgetCacheKeyGeneratorClassName] = $keyGenerator;
        }

        $key = static::$widgetKeyGeneratorCache[$widgetCacheKeyGeneratorClassName]->generateCacheKey($arguments);

        return md5($widgetClassName . $key);
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
