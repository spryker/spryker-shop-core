<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin;

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
}
