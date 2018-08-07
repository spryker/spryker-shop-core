<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyUserClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface;
use SprykerShop\Yves\ShoppingListPage\Form\ShareShoppingListForm;

class ShareShoppingListDataProvider
{
    const ORDER_BUSINESS_UNIT_SORT_FIELD = 'name';
    const ORDER_BUSINESS_UNIT_SORT_DIRECTION = 'ASC';

    /**
     * @var \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyBusinessUnitClientInterface
     */
    protected $companyBusinessUnitClient;

    /**
     * @var \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @var \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyUserClientInterface $companyUserClient
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface $shoppingListClient
     */
    public function __construct(ShoppingListPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient, ShoppingListPageToCompanyUserClientInterface $companyUserClient, ShoppingListPageToCustomerClientInterface $customerClient, ShoppingListPageToShoppingListClientInterface $shoppingListClient)
    {
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
        $this->companyUserClient = $companyUserClient;
        $this->customerClient = $customerClient;
        $this->shoppingListClient = $shoppingListClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getData(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $customerTransfer = $this->getCustomer();
        $shoppingListTransfer->setIdCompanyUser($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser());

        $shoppingListTransfer = $this->shoppingListClient->getShoppingList($shoppingListTransfer);

        $this->addAvailableCompanyBusinessUnits($shoppingListTransfer, $customerTransfer);
        $this->addAvailableCompanyUsers($shoppingListTransfer, $customerTransfer);

        return $shoppingListTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ShareShoppingListForm::OPTION_PERMISSION_GROUPS => $this->getShoppingListPermissionGroups(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer(): CustomerTransfer
    {
        return $this->customerClient->getCustomer();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function addAvailableCompanyBusinessUnits(ShoppingListTransfer $shoppingListTransfer, CustomerTransfer $customerTransfer)
    {
        $companyBusinessUnitIds = [];
        $availableCompanyBusinessUnitCollection = $this->getCompanyBusinessUnitCollection($customerTransfer);

        foreach ($shoppingListTransfer->getCompanyBusinessUnits() as $shoppingListCompanyBusinessUnitTransfer) {
            $companyBusinessUnitIds[] = $shoppingListCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit();
        }

        foreach ($availableCompanyBusinessUnitCollection->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            if (!in_array($companyBusinessUnitTransfer->getIdCompanyBusinessUnit(), $companyBusinessUnitIds)) {
                $shoppingListCompanyBusinessUnit = (new ShoppingListCompanyBusinessUnitTransfer)
                    ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                    ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
                    ->setCompanyBusinessUnitName($companyBusinessUnitTransfer->getName());

                $shoppingListTransfer->addCompanyBusinessUnits($shoppingListCompanyBusinessUnit);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function addAvailableCompanyUsers(ShoppingListTransfer $shoppingListTransfer, CustomerTransfer $customerTransfer)
    {
        $companyUserIds = [];
        $availableCompanyUserCollection = $this->getCompanyUserCollection($customerTransfer);

        foreach ($shoppingListTransfer->getCompanyUsers() as $shoppingListCompanyUserTransfer) {
            $companyUserIds[] = $shoppingListCompanyUserTransfer->getIdCompanyUser();
        }

        foreach ($availableCompanyUserCollection->getCompanyUsers() as $companyUserTransfer) {
            if (!in_array($companyUserTransfer->getIdCompanyUser(), $companyUserIds)) {
                $customerFullName = $companyUserTransfer->getCustomer()->getFirstName() . ' ' .
                    $companyUserTransfer->getCustomer()->getLastName();

                $shoppingListCompanyUser = (new ShoppingListCompanyUserTransfer)
                    ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
                    ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
                    ->setCustomerFullName($customerFullName);

                $shoppingListTransfer->addCompanyUsers($shoppingListCompanyUser);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function getCompanyBusinessUnitCollection(CustomerTransfer $customerTransfer): CompanyBusinessUnitCollectionTransfer
    {
        $idCompany = $customerTransfer->requireCompanyUserTransfer()->getCompanyUserTransfer()->getFkCompany();
        $filter = (new FilterTransfer)
            ->setOrderBy(self::ORDER_BUSINESS_UNIT_SORT_FIELD)
            ->setOrderDirection(self::ORDER_BUSINESS_UNIT_SORT_DIRECTION);

        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany($idCompany)
            ->setFilter($filter);

        return $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    protected function getCompanyUserCollection(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer
    {
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setIdCompany($customerTransfer->requireCompanyUserTransfer()->getCompanyUserTransfer()->getFkCompany());

        return $this->companyUserClient->getCompanyUserCollection($companyUserCriteriaFilterTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer[]
     */
    protected function getShoppingListPermissionGroups(): array
    {
        $shoppingListPermissionGroups = $this->shoppingListClient
            ->getShoppingListPermissionGroupCollection()
            ->getPermissionGroups();

        return $this->mapPermissionGroupsToOptions($shoppingListPermissionGroups);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer[]|\ArrayObject $permissionGroups
     *
     * @return array
     */
    protected function mapPermissionGroupsToOptions(ArrayObject $permissionGroups): array
    {
        $permissionGroupOptions = [0 => 'NO_ACCESS'];
        foreach ($permissionGroups as $permissionGroupTransfer) {
            $permissionGroupOptions[$permissionGroupTransfer->getIdShoppingListPermissionGroup()]
                = $permissionGroupTransfer->getName();
        }

        return $permissionGroupOptions;
    }
}
