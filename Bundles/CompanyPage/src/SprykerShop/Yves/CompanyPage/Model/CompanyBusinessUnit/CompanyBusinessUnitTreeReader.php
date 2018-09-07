<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeCollectionTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;

class CompanyBusinessUnitTreeReader implements CompanyBusinessUnitTreeReaderInterface
{
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
    public function getCustomerCompanyBusinessUnitTree(): array
    {
        $customerTransfer = $this->customerClient->getCustomer();
        $customerCompanyBusinessUnitTree = $this->companyBusinessUnitClient->getCustomerCompanyBusinessUnitTree($customerTransfer);
        $customerCompanyBusinessUnitTree = $this->mapTreeToArray($customerCompanyBusinessUnitTree);

        return $customerCompanyBusinessUnitTree;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeCollectionTransfer $customerCompanyBusinessUnitTree
     *
     * @return array
     */
    protected function mapTreeToArray(CompanyBusinessUnitTreeNodeCollectionTransfer $customerCompanyBusinessUnitTree): array
    {
        $companyBusinessUnitTreeNodes = [];
        foreach ($customerCompanyBusinessUnitTree->getCompanyBusinessUnitTreeNodes() as $companyBusinessUnitTreeNode) {
            $companyBusinessUnitTreeNodeArray = $companyBusinessUnitTreeNode->getCompanyBusinessUnit()->toArray();
            $companyBusinessUnitTreeNodeArray[static::LEVEL_KEY] = $companyBusinessUnitTreeNode->getLevel();

            $children = $this->mapTreeToArray($companyBusinessUnitTreeNode->getChildren());

            $idCompanyBusinessUnit = $companyBusinessUnitTreeNode->getCompanyBusinessUnit()->getIdCompanyBusinessUnit();
            $companyBusinessUnitTreeNodeArray[static::CHILDREN_KEY] = $children ? $children : null;
            $companyBusinessUnitTreeNodes[$idCompanyBusinessUnit] = $companyBusinessUnitTreeNodeArray;
        }

        return $companyBusinessUnitTreeNodes;
    }
}
