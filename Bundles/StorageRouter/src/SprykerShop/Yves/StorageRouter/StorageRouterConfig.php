<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StorageRouter;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\StorageRouter\StorageRouterConstants;

class StorageRouterConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns a list of supported stores for Route manipulation.
     * - Will be used to strip of store information from a route before a route is matched.
     *
     * @api
     *
     * @example Incoming URL `/DE/home` will be manipulated to `/home` because the router only knows URL's without any optional pre/suffix.
     *
     * @see \Spryker\Yves\Router\Plugin\RouterEnhancer\StorePrefixRouterEnhancerPlugin
     *
     * @return array<string>
     */
    public function getAllowedStores(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns true if the store routing is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isStoreRoutingEnabled(): bool
    {
        return $this->get(StorageRouterConstants::IS_STORE_ROUTING_ENABLED, false);
    }
}
