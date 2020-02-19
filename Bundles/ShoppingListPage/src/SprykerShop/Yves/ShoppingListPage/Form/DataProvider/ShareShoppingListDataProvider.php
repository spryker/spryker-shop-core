<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
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
    protected const GLOSSARY_KEY_PERMISSIONS = 'customer.account.shopping_list.permissions';

    protected const ORDER_BUSINESS_UNIT_SORT_FIELD = 'name';
    protected const ORDER_BUSINESS_UNIT_SORT_DIRECTION = 'ASC';

    protected const PERMISSION_NO_ACCESS = 'NO_ACCESS';

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
    public function __construct(
        ShoppingListPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient,
        ShoppingListPageToCompanyUserClientInterface $companyUserClient,
        ShoppingListPageToCustomerClientInterface $customerClient,
        ShoppingListPageToShoppingListClientInterface $shoppingListClient
    ) {
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

        $shoppingListTransfer = $this->expandSharedCompanyUsers($shoppingListTransfer, $customerTransfer);
        $shoppingListTransfer = $this->sortShoppingListCompanyUsers($shoppingListTransfer);

        $shoppingListTransfer = $this->expandSharedCompanyBusinessUnits($shoppingListTransfer, $customerTransfer);
        $shoppingListTransfer = $this->sortShoppingListCompanyBusinessUnit($shoppingListTransfer);

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
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function expandSharedCompanyUsers(
        ShoppingListTransfer $shoppingListTransfer,
        CustomerTransfer $customerTransfer
    ): ShoppingListTransfer {
        $indexedShoppingListCompanyUserTransfers = $this->indexShoppingListCompanyUsers($shoppingListTransfer);
        $shoppingListCompanyUserTransfers = [];
        $companyUserTransfers = $this->getCompanyUserCollection($customerTransfer)->getCompanyUsers();

        foreach ($companyUserTransfers as $companyUserTransfer) {
            $idCompanyUser = $companyUserTransfer->getIdCompanyUser();

            if ($companyUserTransfer->getCustomer()->getCustomerReference() === $shoppingListTransfer->getCustomerReference()) {
                continue;
            }

            if (isset($indexedShoppingListCompanyUserTransfers[$idCompanyUser])) {
                $shoppingListCompanyUserTransfer = $indexedShoppingListCompanyUserTransfers[$idCompanyUser];
                $shoppingListCompanyUserTransfer->setCompanyUser($companyUserTransfer);

                $shoppingListCompanyUserTransfers[$idCompanyUser] = $shoppingListCompanyUserTransfer;

                continue;
            }

            $shoppingListCompanyUserTransfers[$idCompanyUser] = $this->createShoppingListCompanyUser(
                $shoppingListTransfer,
                $companyUserTransfer
            );
        }

        $shoppingListTransfer->setSharedCompanyUsers(new ArrayObject($shoppingListCompanyUserTransfers));

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer
     */
    protected function createShoppingListCompanyUser(
        ShoppingListTransfer $shoppingListTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): ShoppingListCompanyUserTransfer {
        return (new ShoppingListCompanyUserTransfer())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setCompanyUser($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function sortShoppingListCompanyUsers(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListTransfer->getSharedCompanyUsers()->uasort(
            function (ShoppingListCompanyUserTransfer $firstUserTransfer, ShoppingListCompanyUserTransfer $secondUserTransfer) {
                return strcmp($firstUserTransfer->getCompanyUser()->getCustomer()->getFirstName(), $secondUserTransfer->getCompanyUser()->getCustomer()->getFirstName());
            }
        );

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function expandSharedCompanyBusinessUnits(
        ShoppingListTransfer $shoppingListTransfer,
        CustomerTransfer $customerTransfer
    ): ShoppingListTransfer {
        $shoppingListCompanyBusinessUnits = $this->indexShoppingListCompanyBusinessUnits($shoppingListTransfer);
        $companyBusinessUnits = $this->getCompanyBusinessUnitCollection($customerTransfer)->getCompanyBusinessUnits();

        foreach ($companyBusinessUnits as $companyBusinessUnitTransfer) {
            $idCompanyBusinessUnit = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();

            if (isset($shoppingListCompanyBusinessUnits[$idCompanyBusinessUnit])) {
                $shoppingListCompanyBusinessUnit = $shoppingListCompanyBusinessUnits[$idCompanyBusinessUnit];
                $shoppingListCompanyBusinessUnit->setCompanyBusinessUnit($companyBusinessUnitTransfer);

                $shoppingListCompanyBusinessUnits[$idCompanyBusinessUnit] = $shoppingListCompanyBusinessUnit;

                continue;
            }

            $shoppingListCompanyBusinessUnits[$idCompanyBusinessUnit] = $this->createShoppingListCompanyBusinessUnit(
                $shoppingListTransfer,
                $companyBusinessUnitTransfer
            );
        }

        $shoppingListTransfer->setSharedCompanyBusinessUnits(new ArrayObject($shoppingListCompanyBusinessUnits));

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer[]
     */
    protected function indexShoppingListCompanyUsers(ShoppingListTransfer $shoppingListTransfer): array
    {
        $indexedShoppingListCompanyUserTransfers = [];

        foreach ($shoppingListTransfer->getSharedCompanyUsers() as $shoppingListCompanyUserTransfer) {
            $indexedShoppingListCompanyUserTransfers[$shoppingListCompanyUserTransfer->getIdCompanyUser()] = $shoppingListCompanyUserTransfer;
        }

        return $indexedShoppingListCompanyUserTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer[]
     */
    protected function indexShoppingListCompanyBusinessUnits(ShoppingListTransfer $shoppingListTransfer): array
    {
        $sharedCompanyBusinessUnits = [];

        foreach ($shoppingListTransfer->getSharedCompanyBusinessUnits() as $shoppingListCompanyBusinessUnitTransfer) {
            $sharedCompanyBusinessUnits[$shoppingListCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit()] = $shoppingListCompanyBusinessUnitTransfer;
        }

        return $sharedCompanyBusinessUnits;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer
     */
    protected function createShoppingListCompanyBusinessUnit(
        ShoppingListTransfer $shoppingListTransfer,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): ShoppingListCompanyBusinessUnitTransfer {
        return (new ShoppingListCompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setCompanyBusinessUnit($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function sortShoppingListCompanyBusinessUnit(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListTransfer->getSharedCompanyBusinessUnits()->uasort(
            function (ShoppingListCompanyBusinessUnitTransfer $firstBusinessUnitTransfer, ShoppingListCompanyBusinessUnitTransfer $secondBusinessUnitTransfer) {
                return strcmp($firstBusinessUnitTransfer->getCompanyBusinessUnit()->getName(), $secondBusinessUnitTransfer->getCompanyBusinessUnit()->getName());
            }
        );

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function getCompanyBusinessUnitCollection(CustomerTransfer $customerTransfer): CompanyBusinessUnitCollectionTransfer
    {
        $idCompany = $customerTransfer->requireCompanyUserTransfer()->getCompanyUserTransfer()->getFkCompany();
        $filter = (new FilterTransfer())
            ->setOrderBy(static::ORDER_BUSINESS_UNIT_SORT_FIELD)
            ->setOrderDirection(static::ORDER_BUSINESS_UNIT_SORT_DIRECTION);

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
            ->setIsActive(true)
            ->setIdCompany($customerTransfer->requireCompanyUserTransfer()->getCompanyUserTransfer()->getFkCompany());

        return $this->companyUserClient->getCompanyUserCollection($companyUserCriteriaFilterTransfer);
    }

    /**
     * @return int[]
     */
    protected function getShoppingListPermissionGroups(): array
    {
        $shoppingListPermissionGroups = $this->shoppingListClient
            ->getShoppingListPermissionGroups()
            ->getPermissionGroups();

        return $this->mapPermissionGroupsToOptions($shoppingListPermissionGroups);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer[]|\ArrayObject $permissionGroups
     *
     * @return int[]
     */
    protected function mapPermissionGroupsToOptions(ArrayObject $permissionGroups): array
    {
        $permissionGroupOptions = [static::GLOSSARY_KEY_PERMISSIONS . '.' . static::PERMISSION_NO_ACCESS => 0];
        foreach ($permissionGroups as $permissionGroupTransfer) {
            $permissionGroupOptions[static::GLOSSARY_KEY_PERMISSIONS . '.' . $permissionGroupTransfer->getName()]
                = $permissionGroupTransfer->getIdShoppingListPermissionGroup();
        }

        return $permissionGroupOptions;
    }
}
