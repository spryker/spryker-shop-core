<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CartPage\Plugin;

use Spryker\Client\Permission\Dependency\Plugin\PermissionPluginInterface;

class ChangeCartItemPermissionPlugin implements PermissionPluginInterface
{
    public const KEY = 'ChangeCartItemPermissionPlugin';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
