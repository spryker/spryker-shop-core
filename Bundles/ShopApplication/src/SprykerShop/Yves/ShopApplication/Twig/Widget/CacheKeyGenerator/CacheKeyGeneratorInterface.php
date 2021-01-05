<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig\Widget\CacheKeyGenerator;

interface CacheKeyGeneratorInterface
{
    /**
     * @param string $widgetClassName
     * @param array $arguments
     *
     * @return string|null
     */
    public function generateCacheKey(string $widgetClassName, array $arguments): ?string;
}
