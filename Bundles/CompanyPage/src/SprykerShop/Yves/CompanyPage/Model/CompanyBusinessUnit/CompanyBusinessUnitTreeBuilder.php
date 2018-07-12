<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;

class CompanyBusinessUnitTreeBuilder implements CompanyBusinessUnitTreeBuilderInterface
{
    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface
     */
    protected $companyBusinessUnitClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     */
    public function __construct(
        CompanyPageToCustomerClientInterface $customerClient,
        CompanyPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
    ) {
        $this->customerClient = $customerClient;
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyBusinessUnitTree(): CompanyBusinessUnitCollectionTransfer
    {
        $idCompany = $this->customerClient->getCustomer()->getCompanyUserTransfer()->getFkCompany();
        $companyBusinessUnits = $this->getCompanyBusinessUnites($idCompany);

        return $companyBusinessUnits;
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function getCompanyBusinessUnites(int $idCompany): CompanyBusinessUnitCollectionTransfer
    {
        $criteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompany($idCompany);

        return $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection($criteriaFilterTransfer);
    }
}
