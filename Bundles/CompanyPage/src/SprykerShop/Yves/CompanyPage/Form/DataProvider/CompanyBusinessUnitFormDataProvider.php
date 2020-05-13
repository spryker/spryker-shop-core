<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Store\CompanyPageToKernelStoreInterface;
use SprykerShop\Yves\CompanyPage\Form\CompanyBusinessUnitForm;

class CompanyBusinessUnitFormDataProvider
{
    protected const COMPANY_UNIT_ADDRESS_KEY = '%s %s %s %s, %s';

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface
     */
    protected $businessUnitClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface
     */
    protected $companyUnitAddressClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Store\CompanyPageToKernelStoreInterface
     */
    protected $store;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface $businessUnitClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface $companyUnitAddressClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Store\CompanyPageToKernelStoreInterface $store
     */
    public function __construct(
        CompanyPageToCompanyBusinessUnitClientInterface $businessUnitClient,
        CompanyPageToCompanyUnitAddressClientInterface $companyUnitAddressClient,
        CompanyPageToGlossaryStorageClientInterface $glossaryStorageClient,
        CompanyPageToKernelStoreInterface $store
    ) {
        $this->businessUnitClient = $businessUnitClient;
        $this->companyUnitAddressClient = $companyUnitAddressClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->store = $store;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param int|null $idCompanyBusinessUnit
     *
     * @return array
     */
    public function getData(CompanyUserTransfer $companyUserTransfer, ?int $idCompanyBusinessUnit = null): array
    {
        if ($idCompanyBusinessUnit === null) {
            return $this->getDefaultBusinessUnitData($companyUserTransfer);
        }

        $companyBusinessUnitTransfer = $this->loadCompanyBusinessUnitTransfer($idCompanyBusinessUnit);

        $addressCollection = $this->companyUnitAddressClient->getCompanyUnitAddressCollection(
            $this->prepareCompanyUnitAddressCriteriaFilterTransfer(
                $companyBusinessUnitTransfer->getFkCompany(),
                $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
            )
        );

        $companyBusinessUnitTransfer->setAddressCollection($addressCollection);

        return $companyBusinessUnitTransfer->modifiedToArray();
    }

    /**
     * @param int|null $idCompany
     * @param int|null $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer
     */
    protected function prepareCompanyUnitAddressCriteriaFilterTransfer(
        ?int $idCompany = null,
        ?int $idCompanyBusinessUnit = null
    ): CompanyUnitAddressCriteriaFilterTransfer {
        $companyUnitAddressCriteriaFilter = new CompanyUnitAddressCriteriaFilterTransfer();

        if ($idCompany) {
            $companyUnitAddressCriteriaFilter->setIdCompany($idCompany);
        }

        if ($idCompanyBusinessUnit) {
            $companyUnitAddressCriteriaFilter->setIdCompanyBusinessUnit($idCompanyBusinessUnit);
        }

        return $companyUnitAddressCriteriaFilter;
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
    public function getOptions(CompanyUserTransfer $companyUserTransfer, ?int $idCompanyBusinessUnit = null): array
    {
        $companyUserTransfer->requireFkCompany();

        return [
            CompanyBusinessUnitForm::FIELD_COMPANY_UNIT_ADDRESSES => $this->getCompanyUnitAddresses($companyUserTransfer),
            CompanyBusinessUnitForm::FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT => $this->getCompanyBusinessUnits($companyUserTransfer, $idCompanyBusinessUnit),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param int|null $idCompanyBusinessUnit
     *
     * @return array
     */
    protected function getCompanyBusinessUnits(CompanyUserTransfer $companyUserTransfer, ?int $idCompanyBusinessUnit = null): array
    {
        $idCompany = $companyUserTransfer->getFkCompany();
        $companyBusinessUnitCollection = $this->getCompanyBusinessUnitCollection($idCompany);

        $businessUnits = [];
        foreach ($companyBusinessUnitCollection->getCompanyBusinessUnits() as $companyBusinessUnit) {
            if ($idCompanyBusinessUnit === $companyBusinessUnit->getIdCompanyBusinessUnit()) {
                continue;
            }
            $businessUnits[$companyBusinessUnit->getIdCompanyBusinessUnit()] = $companyBusinessUnit->getName();
        }

        return $businessUnits;
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyBusinessUnitCollection(int $idCompany): CompanyBusinessUnitCollectionTransfer
    {
        $criteriaFilterTransfer = $this->createCompanyBusinessUnitCriteriaFilterTransfer($idCompany);

        $companyBusinessUnitCollection = $this->businessUnitClient->getCompanyBusinessUnitCollection(
            $criteriaFilterTransfer
        );

        return $companyBusinessUnitCollection;
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
     * @return string[]
     */
    protected function getCompanyUnitAddresses(CompanyUserTransfer $companyUserTransfer): array
    {
        $idCompany = $companyUserTransfer->getFkCompany();
        $criteriaFilterTransfer = $this->createCompanyUnitAddressCriteriaFilterTransfer($idCompany);

        $companyUnitAddressCollection = $this->companyUnitAddressClient->getCompanyUnitAddressCollection($criteriaFilterTransfer);

        $companyUnitAddresses = [];
        foreach ($companyUnitAddressCollection->getCompanyUnitAddresses() as $companyUnitAddress) {
            $countryName = $this->getTranslatedCountryNameByIso2Code($companyUnitAddress->getIso2Code());
            $companyAddressValue = sprintf(
                static::COMPANY_UNIT_ADDRESS_KEY,
                $companyUnitAddress->getAddress1(),
                $companyUnitAddress->getAddress2(),
                $companyUnitAddress->getZipCode(),
                $companyUnitAddress->getCity(),
                $countryName
            );
            $companyUnitAddresses[$companyUnitAddress->getIdCompanyUnitAddress()] = $companyAddressValue;
        }

        return $companyUnitAddresses;
    }

    /**
     * @param string $iso2Code
     *
     * @return string
     */
    protected function getTranslatedCountryNameByIso2Code(string $iso2Code): string
    {
        $translationKey = CompanyUnitAddressFormDataProvider::COUNTRY_GLOSSARY_PREFIX . $iso2Code;
        $currentLocale = $this->store->getCurrentLocale();

        return $this->glossaryStorageClient->translate($translationKey, $currentLocale);
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
