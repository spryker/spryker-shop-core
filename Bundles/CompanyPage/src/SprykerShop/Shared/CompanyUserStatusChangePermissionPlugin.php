<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\CompanyPage\Plugin;

use Spryker\Shared\PermissionExtension\Dependency\Plugin\PermissionPluginInterface;

class CompanyUserStatusChangePermissionPlugin implements PermissionPluginInterface
{
    public const KEY = 'CompanyUserStatusChangePermissionPlugin';

    /**
     * {@inheritdoc}
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
