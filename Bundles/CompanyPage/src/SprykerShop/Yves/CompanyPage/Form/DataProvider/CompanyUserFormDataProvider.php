<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface;
use SprykerShop\Yves\CompanyPage\Form\CompanyUserForm;

class CompanyUserFormDataProvider
{
    /**
     * @var string
     */
    protected const KEY_ID_COMPANY_ROLE = 'id_company_role';

    /**
     * @var string
     */
    protected const KEY_ROLES = 'roles';

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
     * @param array<string, mixed> $options
     *
     * @return array
     */
    public function getData(int $idCompany, ?int $idCompanyUser = null, array $options = []): array
    {
        if ($idCompanyUser === null) {
            return $this->getDefaultCompanyUserData($idCompany, $options);
        }

        $companyUserTransfer = $this->loadCompanyUserTransfer($idCompanyUser);
        $customerTransfer = $companyUserTransfer->getCustomer();

        return array_merge(
            $companyUserTransfer->toArray(),
            $customerTransfer->toArray(),
        );
    }

    /**
     * @param int $idCompany
     *
     * @return array<string, mixed>
     */
    public function getOptions(int $idCompany): array
    {
        $companyRoleCollectionTransfer = $this->getCompanyRoleCollectionByIdCompany($idCompany);

        return [
            CompanyUserForm::OPTION_BUSINESS_UNIT_CHOICES => $this->getAvailableBusinessUnits($idCompany),
            CompanyUserForm::OPTION_COMPANY_ROLE_CHOICES => $this->getAvailableCompanyRoleIds($companyRoleCollectionTransfer),
            CompanyUserForm::OPTION_DEFAULT_COMPANY_ROLE_ID => $this->findDefaultCompanyRoleId($companyRoleCollectionTransfer),
        ];
    }

    /**
     * @param int $idCompany
     * @param array<string, mixed> $options
     *
     * @return array
     */
    protected function getDefaultCompanyUserData(int $idCompany, array $options = []): array
    {
        return [
            CompanyUserForm::FIELD_FK_COMPANY => $idCompany,
            CompanyUserForm::FIELD_COMPANY_ROLE_COLLECTION => $this->getCompanyRoleCollection($options),
        ];
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array
     */
    protected function getCompanyRoleCollection(array $options): array
    {
        $companyRoleCollection = [];

        $defaultCompanyRoleId = $options[CompanyUserForm::OPTION_DEFAULT_COMPANY_ROLE_ID] ?? null;
        if ($defaultCompanyRoleId !== null) {
            $companyRoleCollection[] = [
                static::KEY_ID_COMPANY_ROLE => $defaultCompanyRoleId,
            ];
        }

        return [
            static::KEY_ROLES => $companyRoleCollection,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
     *
     * @return int|null
     */
    protected function findDefaultCompanyRoleId(CompanyRoleCollectionTransfer $companyRoleCollectionTransfer): ?int
    {
        foreach ($companyRoleCollectionTransfer->getRoles() as $companyRoleTransfer) {
            if ($companyRoleTransfer->getIsDefault()) {
                return $companyRoleTransfer->getIdCompanyRole();
            }
        }

        return null;
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    protected function getCompanyRoleCollectionByIdCompany(int $idCompany): CompanyRoleCollectionTransfer
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
     * @return array<string>
     */
    protected function getAvailableBusinessUnits(int $idCompany): array
    {
        $criteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompany($idCompany);

        $companyBusinessUnitCollection = $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection(
            $criteriaFilterTransfer,
        );

        $businessUnits = [];
        foreach ($companyBusinessUnitCollection->getCompanyBusinessUnits() as $companyBusinessUnit) {
            $businessUnits[$companyBusinessUnit->getIdCompanyBusinessUnit()] = sprintf(
                '%s (ID: %d)',
                $companyBusinessUnit->getName(),
                $companyBusinessUnit->getIdCompanyBusinessUnit(),
            );
        }

        return $businessUnits;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollection
     *
     * @return array<int> Keys are role names
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
