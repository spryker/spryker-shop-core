<?php

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
