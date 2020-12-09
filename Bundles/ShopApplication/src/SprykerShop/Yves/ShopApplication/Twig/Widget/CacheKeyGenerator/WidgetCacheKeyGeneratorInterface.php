<?php

namespace SprykerShop\Yves\ShopApplication\Twig\Widget\CacheKeyGenerator;


interface WidgetCacheKeyGeneratorInterface
{
    /**
     * @param string $widgetClassName
     * @param array $arguments
     *
     * @return string
     */
    public function generateCacheKey(string $widgetClassName, array $arguments): string;
}
