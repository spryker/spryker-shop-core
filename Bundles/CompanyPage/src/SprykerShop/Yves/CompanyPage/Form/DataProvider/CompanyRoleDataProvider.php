<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface;
use SprykerShop\Yves\CompanyPage\Form\CompanyRoleForm;

class CompanyRoleDataProvider
{
    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface
     */
    protected $companyRoleClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface $companyRoleClient
     */
    public function __construct(CompanyPageToCompanyRoleClientInterface $companyRoleClient)
    {
        $this->companyRoleClient = $companyRoleClient;
    }

    /**
     * @param int $fkCompany
     * @param int|null $idCompanyRole
     *
     * @return array
     */
    public function getData(int $fkCompany, ?int $idCompanyRole = null): array
    {
        if ($idCompanyRole === null) {
            return $this->getDefaultCompanyRoleData($fkCompany);
        }

        if ($idCompanyRole) {
            $companyRoleTransfer = new CompanyRoleTransfer();
            $companyRoleTransfer->setIdCompanyRole($idCompanyRole);
            $companyRole = $this->companyRoleClient->getCompanyRoleById($companyRoleTransfer);

            return $companyRole->modifiedToArray();
        }

        return [];
    }

    /**
     * @param int $fkCompany
     *
     * @return array
     */
    protected function getDefaultCompanyRoleData($fkCompany): array
    {
        return [
            CompanyRoleForm::FIELD_FK_COMPANY => $fkCompany,
        ];
    }
}
