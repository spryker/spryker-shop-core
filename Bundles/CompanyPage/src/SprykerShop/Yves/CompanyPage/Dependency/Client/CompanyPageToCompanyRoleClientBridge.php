<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Dependency\Client;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRolePermissionResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;

class CompanyPageToCompanyRoleClientBridge implements CompanyPageToCompanyRoleClientInterface
{
    /**
     * @var \Spryker\Client\CompanyRole\CompanyRoleClientInterface
     */
    protected $companyRoleClient;

    /**
     * @param \Spryker\Client\CompanyRole\CompanyRoleClientInterface $companyRoleClient
     */
    public function __construct($companyRoleClient)
    {
        $this->companyRoleClient = $companyRoleClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function createCompanyRole(CompanyRoleTransfer $companyRoleUserTransfer): CompanyRoleResponseTransfer
    {
        return $this->companyRoleClient->createCompanyRole($companyRoleUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getCompanyRoleCollection(
        CompanyRoleCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyRoleCollectionTransfer {
        return $this->companyRoleClient->getCompanyRoleCollection($criteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        return $this->companyRoleClient->getCompanyRoleById($companyRoleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleUserTransfer
     *
     * @return void
     */
    public function updateCompanyRole(CompanyRoleTransfer $companyRoleUserTransfer): void
    {
        $this->companyRoleClient->updateCompanyRole($companyRoleUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function deleteCompanyRole(CompanyRoleTransfer $companyRoleUserTransfer): CompanyRoleResponseTransfer
    {
        return $this->companyRoleClient->deleteCompanyRole($companyRoleUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyRolePermissions(CompanyRoleTransfer $companyRoleTransfer): PermissionCollectionTransfer
    {
        return $this->companyRoleClient->findCompanyRolePermissions($companyRoleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function findPermissionByIdCompanyRoleByIdPermission(PermissionTransfer $permissionTransfer): PermissionTransfer
    {
        return $this->companyRoleClient->findPermissionByIdCompanyRoleByIdPermission($permissionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRolePermissionResponseTransfer
     */
    public function updateCompanyRolePermission(PermissionTransfer $permissionTransfer): CompanyRolePermissionResponseTransfer
    {
        return $this->companyRoleClient->updateCompanyRolePermission($permissionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function saveCompanyUser(CompanyUserTransfer $companyUserTransfer): void
    {
        $this->companyRoleClient->saveCompanyUser($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findNonInfrastructuralCompanyRolePermissionsByIdCompanyRole(
        CompanyRoleTransfer $companyRoleTransfer
    ): PermissionCollectionTransfer {
        return $this->companyRoleClient->findNonInfrastructuralCompanyRolePermissionsByIdCompanyRole($companyRoleTransfer);
    }
}
