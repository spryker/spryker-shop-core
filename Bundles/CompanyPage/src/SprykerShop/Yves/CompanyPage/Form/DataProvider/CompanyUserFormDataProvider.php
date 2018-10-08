<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface;
use SprykerShop\Yves\CompanyPage\Form\CompanyUserForm;

class CompanyUserFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface
     */
    protected $companyBusinessUnitClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface
     */
    protected $companyRoleClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface $companyUserClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface $companyRoleClient
     */
    public function __construct(
        CompanyPageToCompanyUserClientInterface $companyUserClient,
        CompanyPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient,
        CompanyPageToCompanyRoleClientInterface $companyRoleClient
    ) {
        $this->companyUserClient = $companyUserClient;
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
        $this->companyRoleClient = $companyRoleClient;
    }

    /**
     * @param int $idCompany
     * @param int|null $idCompanyUser
     *
     * @return array
     */
    public function getData(int $idCompany, ?int $idCompanyUser = null): array
    {
        if ($idCompanyUser === null) {
            return $this->getDefaultCompanyUserData($idCompany);
        }

        $companyUserTransfer = $this->loadCompanyUserTransfer($idCompanyUser);
        $customerTransfer = $companyUserTransfer->getCustomer();

        return array_merge(
            $companyUserTransfer->toArray(),
            $customerTransfer->toArray()
        );
    }

    /**
     * @param int $idCompany
     *
     * @return array
     */
    public function getOptions(int $idCompany): array
    {
        return [
            CompanyUserForm::OPTION_BUSINESS_UNIT_CHOICES => $this->getAvailableBusinessUnits($idCompany),
            CompanyUserForm::OPTION_COMPANY_ROLE_CHOICES => $this->getAvailableCompanyRoleIds($idCompany),
        ];
    }

    /**
     * @param int $idCompany
     *
     * @return array
     */
    protected function getDefaultCompanyUserData(int $idCompany): array
    {
        $companyRoleCollection = $this->getCompanyRoleCollectionWithDefaultCompanyRoleOnly($idCompany)->toArray();

        return [
            CompanyUserForm::FIELD_FK_COMPANY => $idCompany,
            CompanyUserForm::FIELD_COMPANY_ROLE_COLLECTION => $companyRoleCollection,
        ];
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    protected function getCompanyRoleCollectionWithDefaultCompanyRoleOnly(int $idCompany): CompanyRoleCollectionTransfer
    {
        $companyRoleCollection = new CompanyRoleCollectionTransfer();

        $defaultIdCompanyRole = $this->findDefaultIdCompanyRole($idCompany);
        if ($defaultIdCompanyRole === null) {
            return $companyRoleCollection;
        }

        $companyRoleTransfer = (new CompanyRoleTransfer())
            ->setIdCompanyRole($defaultIdCompanyRole);

        $companyRoleCollection->setRoles(new ArrayObject([
            $companyRoleTransfer,
        ]));

        return $companyRoleCollection;
    }

    /**
     * @param int $idCompany
     *
     * @return int|null
     */
    protected function findDefaultIdCompanyRole(int $idCompany): ?int
    {
        $criteriaFilterTransfer = (new CompanyRoleCriteriaFilterTransfer())
            ->setIdCompany($idCompany);

        $companyRoleCollection = $this->companyRoleClient->getCompanyRoleCollection($criteriaFilterTransfer);

        foreach ($companyRoleCollection->getRoles() as $companyRoleTransfer) {
            if ($companyRoleTransfer->getIsDefault()) {
                return $companyRoleTransfer->getIdCompanyRole();
            }
        }

        return null;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function loadCompanyUserTransfer(int $idCompanyUser): CompanyUserTransfer
    {
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->setIdCompanyUser($idCompanyUser);

        return $this->companyUserClient->getCompanyUserById($companyUserTransfer);
    }

    /**
     * @param int $idCompany
     *
     * @return array
     */
    protected function getAvailableBusinessUnits(int $idCompany): array
    {
        $criteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompany($idCompany);

        $companyBusinessUnitCollection = $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection(
            $criteriaFilterTransfer
        );

        $businessUnits = [];
        foreach ($companyBusinessUnitCollection->getCompanyBusinessUnits() as $companyBusinessUnit) {
            $businessUnits[$companyBusinessUnit->getIdCompanyBusinessUnit()] = $companyBusinessUnit->getName();
        }

        return $businessUnits;
    }

    /**
     * @param int $idCompany
     *
     * @return int[] Keys are role names
     */
    protected function getAvailableCompanyRoleIds(int $idCompany): array
    {
        $criteriaFilterTransfer = (new CompanyRoleCriteriaFilterTransfer())
            ->setIdCompany($idCompany);

        $companyRoleCollection = $this->companyRoleClient->getCompanyRoleCollection($criteriaFilterTransfer);

        $roles = [];
        foreach ($companyRoleCollection->getRoles() as $role) {
            $roles[$role->getName()] = $role->getIdCompanyRole();
        }

        return $roles;
    }
}
