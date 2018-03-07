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
use Spryker\Zed\CompanyRole\Business\Model\CompanyRole;

interface CompanyPageToCompanyRoleClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function createCompanyRole(CompanyRoleTransfer $companyRoleUserTransfer): CompanyRoleResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleUserTransfer
     *
     * @return void
     */
    public function updateCompanyRole(CompanyRoleTransfer $companyRoleUserTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleUserTransfer
     *
     * @return void
     */
    public function deleteCompanyRole(CompanyRoleTransfer $companyRoleUserTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getCompanyRoleCollection(
        CompanyRoleCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyRoleCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyRolePermissions(CompanyRoleTransfer $companyRoleTransfer): PermissionCollectionTransfer;

    /**
     * @param PermissionTransfer $permissionTransfer
     *
     * @return PermissionTransfer
     */
    public function findPermissionByIdCompanyRoleByIdPermission(PermissionTransfer $permissionTransfer): PermissionTransfer;

    /**
     * @param PermissionTransfer $permissionTransfer
     *
     * @return CompanyRolePermissionResponseTransfer
     */
    public function updateCompanyRolePermission(PermissionTransfer $permissionTransfer): CompanyRolePermissionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function saveCompanyUser(CompanyUserTransfer $companyUserTransfer): void;
}
