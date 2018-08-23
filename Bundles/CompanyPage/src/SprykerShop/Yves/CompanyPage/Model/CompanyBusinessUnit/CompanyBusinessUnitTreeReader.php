<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitTreeTransfer;
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
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTreeTransfer $customerCompanyBusinessUnitTree
     * @param int|null $idParentCompanyBusinessUnit
     *
     * @return array
     */
    protected function mapTreeToArray(CompanyBusinessUnitTreeTransfer $customerCompanyBusinessUnitTree, ?int $idParentCompanyBusinessUnit = null): array
    {
        $customerCompanyBusinessUnitTreeArray = [];
        foreach ($customerCompanyBusinessUnitTree->getCompanyBusinessUnitTreeItems() as $customerCompanyBusinessUnitTreeItem) {
            $customerCompanyBusinessUnitTreeItemArray = $customerCompanyBusinessUnitTreeItem->getCompanyBusinessUnit()->toArray();
            $customerCompanyBusinessUnitTreeItemArray = array_merge($customerCompanyBusinessUnitTreeItemArray, $customerCompanyBusinessUnitTreeItem->toArray());
            if ($customerCompanyBusinessUnitTreeItem->getFkParentCompanyBusinessUnit() === $idParentCompanyBusinessUnit) {
                $customerCompanyBusinessUnitTreeItemArray[static::LEVEL_KEY] = $customerCompanyBusinessUnitTreeItem->getLevel();
                $customerCompanyBusinessUnitTreeItemArray[static::CHILDREN_KEY] = [];
                $children = $this->mapTreeToArray($customerCompanyBusinessUnitTreeItem->getChildren(), $customerCompanyBusinessUnitTreeItem->getIdCompanyBusinessUnit());
                $customerCompanyBusinessUnitTreeItemArray[static::CHILDREN_KEY] = $children ? $children : null;
                $customerCompanyBusinessUnitTreeArray[$customerCompanyBusinessUnitTreeItem->getIdCompanyBusinessUnit()] = $customerCompanyBusinessUnitTreeItemArray;
            }
        }

        return $customerCompanyBusinessUnitTreeArray;
    }
}
