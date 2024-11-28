<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\StorageRouter;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface StorageRouterConstants
{
    /**
     * Specification:
     *  - Returns true if the store routing is enabled.
     *
     * @api
     *
     * @var string
     */
    public const IS_STORE_ROUTING_ENABLED = 'STORAGE_ROUTER:IS_STORE_ROUTING_ENABLED';
}
