<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin;

/**
 * Generates cache keys for widget instances.
 *
 * If widget has no related plugin, default key generation will be used.
 * In case if you want to disable widget instance caching NULL should  be returned by `WidgetCacheKeyGeneratorPluginInterface::generateCacheKey()` method.
 */
interface WidgetCacheKeyGeneratorPluginInterface
{
    /**
     * Specification:
     * - Generates a cache key for the related widget.
     * - If generated cache key is null, caching is disabled for this widget.
     *
     * @api
     *
     * @param array $arguments
     *
     * @return string|null
     */
    public function generateCacheKey(array $arguments = []): ?string;

    /**
     * Specification:
     * - Returns class name of the widget that we generate cache key for.
     *
     * @api
     *
     * @return string
     */
    public function getRelatedWidgetClassName(): string;
}
