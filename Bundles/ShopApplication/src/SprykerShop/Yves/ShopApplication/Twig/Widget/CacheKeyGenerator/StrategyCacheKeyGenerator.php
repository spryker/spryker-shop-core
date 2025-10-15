<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget\CacheKeyGenerator;

class StrategyCacheKeyGenerator implements CacheKeyGeneratorInterface
{
    /**
     * @var array<\SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface>
     */
    protected static $widgetCacheKeyGeneratorPlugins = [];

    /**
     * @param array<\SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface> $widgetCacheKeyGeneratorPlugins
     */
    public function __construct(array $widgetCacheKeyGeneratorPlugins = [])
    {
        if (!static::$widgetCacheKeyGeneratorPlugins) {
            static::$widgetCacheKeyGeneratorPlugins = $this->indexWidgetCacheKeyGeneratorPlugins($widgetCacheKeyGeneratorPlugins);
        }
    }

    /**
     * @param string $widgetClassName
     * @param array $arguments
     *
     * @return string|null
     */
    public function generateCacheKey(string $widgetClassName, array $arguments): ?string
    {
        if (!isset(static::$widgetCacheKeyGeneratorPlugins[$widgetClassName])) {
            return md5($widgetClassName . $this->argumentSerializer($arguments));
        }

        $key = static::$widgetCacheKeyGeneratorPlugins[$widgetClassName]->generateCacheKey($arguments);

        if ($key === null) {
            return null;
        }

        return md5($widgetClassName . $key);
    }

    /**
     * @param array $arguments
     *
     * @return string
     */
    protected function argumentSerializer(array $arguments): string
    {
        $result = '';
        foreach ($arguments as $argument) {
            if (is_object($argument)) {
                if (get_class($argument) === 'Generated\\Shared\\Transfer\\QuoteTransfer') {
                    $result .= $argument->getIdQuote() . $argument->getCustomerReference();
                    if (method_exists($argument, 'getUuid')) {
                        $result .= $argument->getUuid();
                    }

                    continue;
                }
                if (get_class($argument) === 'Generated\\Shared\\Transfer\\ItemTransfer') {
                    $result .= $argument->getId() . $argument->getGroupKey() . $argument->getSku();

                    continue;
                }
            }
            $result .= serialize($argument);
        }

        return $result;
    }

    /**
     * @param array<\SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface> $widgetCacheKeyGeneratorPlugins
     *
     * @return array<\SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface>
     */
    protected function indexWidgetCacheKeyGeneratorPlugins(array $widgetCacheKeyGeneratorPlugins): array
    {
        $indexedWidgetCacheKeyGeneratorPlugins = [];

        foreach ($widgetCacheKeyGeneratorPlugins as $widgetCacheKeyGeneratorPlugin) {
            $indexedWidgetCacheKeyGeneratorPlugins[$widgetCacheKeyGeneratorPlugin->getWidgetClassName()] = $widgetCacheKeyGeneratorPlugin;
        }

        return $indexedWidgetCacheKeyGeneratorPlugins;
    }
}
