<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\PermissionTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface;

class CompanyRolePermissionDataProvider
{
    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface
     */
    protected $companyRoleClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface $companyRoleClient
     */
    public function __construct(CompanyPageToCompanyRoleClientInterface $companyRoleClient)
    {
        $this->companyRoleClient = $companyRoleClient;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return [
            'data_class' => PermissionTransfer::class,
        ];
    }

    /**
     * @param int $idCompanyRole
     * @param int $idPermission
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function getData(int $idCompanyRole, int $idPermission): PermissionTransfer
    {
        $permissionTransfer = (new PermissionTransfer())
            ->setIdPermission($idPermission)
            ->setIdCompanyRole($idCompanyRole);

        return $this->companyRoleClient->findPermissionByIdCompanyRoleByIdPermission($permissionTransfer);
    }
}
