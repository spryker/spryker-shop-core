<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
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
    public function getData(CompanyUserTransfer $companyUserTransfer, $idCompanyBusinessUnit = null): array
    {
        if ($idCompanyBusinessUnit === null) {
            return $this->getDefaultBusinessUnitData($companyUserTransfer);
        }

        if ($idCompanyBusinessUnit !== null) {
            $companyBusinessUnitResponseTransfer = $this->loadCompanyBusinessUnitTransfer($idCompanyBusinessUnit);
            $companyBusinessUnitTransfer = $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer();

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
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    protected function loadCompanyBusinessUnitTransfer($idCompanyBusinessUnit): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);

        return $this->businessUnitClient->getCompanyBusinessUnitById($companyBusinessUnitTransfer);
    }

}
