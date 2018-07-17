<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface;
use SprykerShop\Yves\CompanyPage\Form\CompanyBusinessUnitForm;

class CompanyBusinessUnitFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface
     */
    protected $businessUnitClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface
     */
    protected $companyUnitAddressClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface $businessUnitClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface $companyUnitAddressClient
     */
    public function __construct(
        CompanyPageToCompanyBusinessUnitClientInterface $businessUnitClient,
        CompanyPageToCompanyUnitAddressClientInterface $companyUnitAddressClient
    ) {
        $this->businessUnitClient = $businessUnitClient;
        $this->companyUnitAddressClient = $companyUnitAddressClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param int|null $idCompanyBusinessUnit
     *
     * @return array
     */
    public function getData(CompanyUserTransfer $companyUserTransfer, $idCompanyBusinessUnit = null): array
    {
        if ($idCompanyBusinessUnit === null) {
            return $this->getDefaultBusinessUnitData($companyUserTransfer);
        }

        if ($idCompanyBusinessUnit !== null) {
            $companyBusinessUnitTransfer = $this->loadCompanyBusinessUnitTransfer($idCompanyBusinessUnit);

            return $companyBusinessUnitTransfer->modifiedToArray();
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array
     */
    protected function getDefaultBusinessUnitData(CompanyUserTransfer $companyUserTransfer): array
    {
        $companyUserTransfer->requireFkCompany();

        return [
            CompanyBusinessUnitForm::FIELD_FK_COMPANY => $companyUserTransfer->getFkCompany(),
        ];
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function loadCompanyBusinessUnitTransfer($idCompanyBusinessUnit): CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);

        return $this->businessUnitClient->getCompanyBusinessUnitById($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param int|null $idCompanyBusinessUnit
     *
     * @return array
     */
    public function getOptions(CompanyUserTransfer $companyUserTransfer, $idCompanyBusinessUnit = null): array
    {
        $companyUserTransfer->requireFkCompany();

        return [
            CompanyBusinessUnitForm::FIELD_FK_COMPANY_PARENT_BUSINESS_UNIT => $this->getCompanyBusinessUnits($companyUserTransfer, $idCompanyBusinessUnit),
            CompanyBusinessUnitForm::FIELD_COMPANY_UNIT_ADDRESSES => $this->getCompanyUnitAddresses($companyUserTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param int|null $idCompanyBusinessUnit
     *
     * @return array
     */
    protected function getCompanyBusinessUnits(CompanyUserTransfer $companyUserTransfer, $idCompanyBusinessUnit = null): array
    {
        $idCompany = $companyUserTransfer->getFkCompany();
        $criteriaFilterTransfer = $this->createCompanyBusinessUnitCriteriaFilterTransfer($idCompany);

        $companyBusinessUnitCollection = $this->businessUnitClient->getCompanyBusinessUnitCollection(
            $criteriaFilterTransfer
        );

        $businessUnits = [];
        foreach ($companyBusinessUnitCollection->getCompanyBusinessUnits() as $companyBusinessUnit) {
            if ($idCompanyBusinessUnit == $companyBusinessUnit->getIdCompanyBusinessUnit()) {
                continue;
            }
            $businessUnits[$companyBusinessUnit->getIdCompanyBusinessUnit()] = $companyBusinessUnit->getName();
        }

        return $businessUnits;
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer
     */
    protected function createCompanyBusinessUnitCriteriaFilterTransfer(int $idCompany): CompanyBusinessUnitCriteriaFilterTransfer
    {
        return (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($idCompany);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array
     */
    protected function getCompanyUnitAddresses(CompanyUserTransfer $companyUserTransfer): array
    {
        $idCompany = $companyUserTransfer->getFkCompany();
        $criteriaFilterTransfer = $this->createCompanyUnitAddressCriteriaFilterTransfer($idCompany);

        $companyUnitAddressCollection = $this->companyUnitAddressClient->getCompanyUnitAddressCollection($criteriaFilterTransfer);

        $companyUnitAddresses = [];
        foreach ($companyUnitAddressCollection->getCompanyUnitAddresses() as $companyUnitAddress) {
            $companyUnitAddresses[$companyUnitAddress->getIdCompanyUnitAddress()] = $companyUnitAddress;
        }

        return $companyUnitAddresses;
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer
     */
    protected function createCompanyUnitAddressCriteriaFilterTransfer(int $idCompany): CompanyUnitAddressCriteriaFilterTransfer
    {
        return (new CompanyUnitAddressCriteriaFilterTransfer())->setIdCompany($idCompany);
    }
}
