<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Store\CompanyPageToKernelStoreInterface;
use SprykerShop\Yves\CompanyPage\Form\CompanyUnitAddressForm;

class CompanyUnitAddressFormDataProvider
{
    public const COUNTRY_GLOSSARY_PREFIX = 'countries.iso.';

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface
     */
    protected $companyUnitAddressClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Store\CompanyPageToKernelStoreInterface
     */
    protected $store;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface $companyUnitAddressClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Store\CompanyPageToKernelStoreInterface $store
     */
    public function __construct(
        CompanyPageToCompanyUnitAddressClientInterface $companyUnitAddressClient,
        CompanyPageToKernelStoreInterface $store
    ) {
        $this->companyUnitAddressClient = $companyUnitAddressClient;
        $this->store = $store;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param int|null $idCompanyUnitAddress
     * @param int|null $idCompanyBusinessUnit
     *
     * @return array
     */
    public function getData(
        CompanyUserTransfer $companyUserTransfer,
        $idCompanyUnitAddress = null,
        $idCompanyBusinessUnit = null
    ): array {
        if ($idCompanyUnitAddress === null) {
            return $this->getDefaultAddressData($companyUserTransfer, $idCompanyBusinessUnit);
        }

        $addressTransfer = $this->loadCompanyUnitAddressTransfer($idCompanyUnitAddress);
        if ($addressTransfer !== null) {
            if ($idCompanyBusinessUnit !== null) {
                $addressTransfer->setFkCompanyBusinessUnit($idCompanyBusinessUnit);
            }

            return $addressTransfer->modifiedToArray();
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param int|null $idCompanyBusinessUnit
     *
     * @return array
     */
    protected function getDefaultAddressData(CompanyUserTransfer $companyUserTransfer, $idCompanyBusinessUnit = null): array
    {
        return [
            CompanyUnitAddressForm::FIELD_FK_COMPANY => $companyUserTransfer->getFkCompany(),
            CompanyUnitAddressForm::FIELD_FK_COMPANY_BUSINESS_UNIT => $idCompanyBusinessUnit,
        ];
    }

    /**
     * @param int|null $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|null
     */
    protected function loadCompanyUnitAddressTransfer($idCompanyUnitAddress = null): ?CompanyUnitAddressTransfer
    {
        $addressTransfer = new CompanyUnitAddressTransfer();
        $addressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        return $this->companyUnitAddressClient->getCompanyUnitAddressById($addressTransfer);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            CompanyUnitAddressForm::OPTION_COUNTRY_CHOICES => $this->getAvailableCountries(),
        ];
    }

    /**
     * @return array
     */
    protected function getAvailableCountries(): array
    {
        $countries = [];

        foreach ($this->store->getCountries() as $iso2Code) {
            $countries[$iso2Code] = static::COUNTRY_GLOSSARY_PREFIX . $iso2Code;
        }

        return $countries;
    }
}
