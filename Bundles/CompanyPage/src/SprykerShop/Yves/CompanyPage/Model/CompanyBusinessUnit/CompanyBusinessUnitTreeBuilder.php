<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;

class CompanyBusinessUnitTreeBuilder implements CompanyBusinessUnitTreeBuilderInterface
{
    protected const FK_PARENT_COMPANY_BUSINESS_UNIT_KEY = 'fk_parent_company_business_unit';
    protected const ID_COMPANY_BUSINESS_UNIT_KEY = 'id_company_business_unit';
    protected const LEVEL_KEY = 'level';
    protected const CHILDREN_KEY = 'children';

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
     * @return array
     */
    public function getCompanyBusinessUnitTree(): array
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if ($customerTransfer === null || $customerTransfer->getCompanyUserTransfer() === null) {
            return [];
        }

        $idCompany = $customerTransfer->getCompanyUserTransfer()->getFkCompany();

        $companyBusinessUnits = $this->getCompanyBusinessUnits($idCompany);

        $companyBusinessUnitTree = $this->buildTree($companyBusinessUnits->getCompanyBusinessUnits());

        return $companyBusinessUnitTree;
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function getCompanyBusinessUnits(int $idCompany): CompanyBusinessUnitCollectionTransfer
    {
        $criteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompany($idCompany);

        return $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection($criteriaFilterTransfer);
    }

    /**
     * @param \ArrayObject $companyBusinessUnits
     * @param int|null $idParentCompanyBusinessUnit
     * @param int $indent
     *
     * @return array
     */
    protected function buildTree(ArrayObject $companyBusinessUnits, ?int $idParentCompanyBusinessUnit = null, int $indent = 0): array
    {
        $tree = [];
        foreach ($companyBusinessUnits as $companyBusinessUnit) {
            $companyBusinessUnitArray = $companyBusinessUnit->toArray();
            if ($companyBusinessUnitArray[static::FK_PARENT_COMPANY_BUSINESS_UNIT_KEY] == $idParentCompanyBusinessUnit) {
                $companyBusinessUnitArray[static::CHILDREN_KEY] = [];
                $companyBusinessUnitArray[static::LEVEL_KEY] = $indent;
                $children = $this->buildTree($companyBusinessUnits, $companyBusinessUnitArray[static::ID_COMPANY_BUSINESS_UNIT_KEY], $indent + 1);
                if ($children) {
                    $companyBusinessUnitArray[static::CHILDREN_KEY] = $children;
                }
                $tree[$companyBusinessUnitArray[static::ID_COMPANY_BUSINESS_UNIT_KEY]] = $companyBusinessUnitArray;
            }
        }

        return $tree;
    }
}
