<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

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
     * @param array|null $options
     *
     * @return array
     */
    public function getData(int $idCompany, ?int $idCompanyUser = null, ?array $options = null): array
    {
        if ($idCompanyUser === null) {
            return $this->getDefaultCompanyUserData($idCompany, $options);
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
        $retrievedCompanyRoleCollection = $this->retrieveCompanyRoleCollectionFromCompanyRoleClient($idCompany);

        return [
            CompanyUserForm::OPTION_BUSINESS_UNIT_CHOICES => $this->getAvailableBusinessUnits($idCompany),
            CompanyUserForm::OPTION_COMPANY_ROLE_CHOICES => $this->getAvailableCompanyRoleIds($retrievedCompanyRoleCollection),
            CompanyUserForm::OPTION_RETRIEVED_COMPANY_ROLES => $retrievedCompanyRoleCollection,
        ];
    }

    /**
     * @param int $idCompany
     * @param array|null $options
     *
     * @return array
     */
    protected function getDefaultCompanyUserData(int $idCompany, ?array $options = null): array
    {
        $companyRolesArray = ($options !== null)
            ? $this->getCompanyRolesArrayWithDefaultCompanyRoleOnly($options)
            : null;

        return [
            CompanyUserForm::FIELD_FK_COMPANY => $idCompany,
            CompanyUserForm::FIELD_COMPANY_ROLE_COLLECTION => $companyRolesArray,
        ];
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function getCompanyRolesArrayWithDefaultCompanyRoleOnly(array $options): array
    {
        $companyRoles = [];

        $defaultCompanyRole = $this->findDefaultCompanyRole($options);
        if ($defaultCompanyRole === null) {
            return $companyRoles;
        }
        $companyRoles[] = $defaultCompanyRole->toArray();

        return [
            CompanyRoleCollectionTransfer::ROLES => $companyRoles,
        ];
    }

    /**
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer|null
     */
    protected function findDefaultCompanyRole(array $options): ?CompanyRoleTransfer
    {
        $retrievedCompanyRoles = $options[CompanyUserForm::OPTION_RETRIEVED_COMPANY_ROLES];

        foreach ($retrievedCompanyRoles->getRoles() as $companyRoleTransfer) {
            if ($companyRoleTransfer->getIsDefault()) {
                return $companyRoleTransfer;
            }
        }

        return null;
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    protected function retrieveCompanyRoleCollectionFromCompanyRoleClient(int $idCompany): CompanyRoleCollectionTransfer
    {
        $criteriaFilterTransfer = new CompanyRoleCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompany($idCompany);

        return $this->companyRoleClient->getCompanyRoleCollection($criteriaFilterTransfer);
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
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollection
     *
     * @return int[] Keys are role names
     */
    protected function getAvailableCompanyRoleIds(CompanyRoleCollectionTransfer $companyRoleCollection): array
    {
        $roles = [];
        foreach ($companyRoleCollection->getRoles() as $role) {
            $roles[$role->getName()] = $role->getIdCompanyRole();
        }

        return $roles;
    }
}
