<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Form\CompanyBusinessUnitForm;

class CompanyBusinessUnitFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface
     */
    protected $businessUnitClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface $businessUnitClient
     */
    public function __construct(
        CompanyPageToCompanyBusinessUnitClientInterface $businessUnitClient
    ) {
        $this->businessUnitClient = $businessUnitClient;
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

        return $companyBusinessUnitTransfer->modifiedToArray();
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
}
