<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CompanyPage\Plugin;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

/**
 * For Client PermissionDependencyProvider::getPermissionPlugins() registration
 */
class CompanyUserStatusChangePermissionPlugin implements PermissionPluginInterface
{
    public const KEY = 'CompanyUserStatusChangePermissionPlugin';

    /**
     * Specification:
     * - Returns plugin name as key which is passed to company account page to manage enable / disable company users permission.
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }
}
