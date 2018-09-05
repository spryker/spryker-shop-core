<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
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
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface $companyUserClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     */
    public function __construct(
        CompanyPageToCompanyUserClientInterface $companyUserClient,
        CompanyPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
    ) {
        $this->companyUserClient = $companyUserClient;
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
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

        if ($idCompanyUser) {
            $companyUserTransfer = $this->loadCompanyUserTransfer($idCompanyUser);
            $customerTransfer = $companyUserTransfer->getCustomer();

            return array_merge(
                $companyUserTransfer->toArray(),
                $customerTransfer->toArray()
            );
        }

        return [];
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
        ];
    }

    /**
     * @param int $idCompany
     *
     * @return array
     */
    protected function getDefaultCompanyUserData(int $idCompany): array
    {
        return [
            CompanyUserForm::FIELD_FK_COMPANY => $idCompany,
        ];
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
}
