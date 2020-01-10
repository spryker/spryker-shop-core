<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CompanyPage\Plugin;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

/**
 * For Zed & Client PermissionDependencyProvider::getPermissionPlugins() registration
 *
 * @deprecated Use `Spryker\Client\CompanyUser\Plugin\CompanyUserStatusChangePermissionPlugin` instead.
 */
class CompanyUserStatusChangePermissionPlugin implements PermissionPluginInterface
{
    public const KEY = 'CompanyUserStatusChangePermissionPlugin';

    /**
     * Specification:
     * - Returns plugin name as key to permission manage for enable / disable status of company users.
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
