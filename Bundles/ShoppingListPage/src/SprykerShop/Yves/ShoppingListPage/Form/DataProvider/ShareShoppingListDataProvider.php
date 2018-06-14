<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyUserClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface;
use SprykerShop\Yves\ShoppingListPage\Form\ShareShoppingListForm;

class ShareShoppingListDataProvider
{
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
     * @param int $idShoppingList
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareRequestTransfer
     */
    public function getData(int $idShoppingList): ShoppingListShareRequestTransfer
    {
        $shoppingListShareRequestTransfer = (new ShoppingListShareRequestTransfer())
            ->setShoppingListOwnerId($this->customerClient->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser())
            ->setIdShoppingList($idShoppingList)
            ->setIdShoppingListPermissionGroup($this->shoppingListClient->getShoppingListPermissionGroup()->getIdShoppingListPermissionGroup());

        return $shoppingListShareRequestTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $customer = $this->getCustomer();
        return [
            ShareShoppingListForm::OPTION_BUSINESS_UNITS => $this->getCompanyUserCompanyBusinessUnits($customer),
            ShareShoppingListForm::OPTION_COMPANY_USERS => $this->getCompanyUserCompanyUsers($customer),
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    protected function getCompanyUserCompanyBusinessUnits(CustomerTransfer $customerTransfer): array
    {
        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany($customerTransfer->requireCompanyUserTransfer()->getCompanyUserTransfer()->getFkCompany());

        $companyBusinessUnits = $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer)->getCompanyBusinessUnits();
        return $this->mapCompanyBusinessUnitsToOptions($companyBusinessUnits);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer[]|\ArrayObject $companyBusinessUnits
     *
     * @return array
     */
    protected function mapCompanyBusinessUnitsToOptions(ArrayObject $companyBusinessUnits): array
    {
        $companyBusinessUnitsOptions = [];
        foreach ($companyBusinessUnits as $companyBusinessUnit) {
            $companyBusinessUnitsOptions[$companyBusinessUnit->getIdCompanyBusinessUnit()] = $companyBusinessUnit->getName();
        }

        return $companyBusinessUnitsOptions;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    protected function getCompanyUserCompanyUsers(CustomerTransfer $customerTransfer): array
    {
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setIdCompany($customerTransfer->requireCompanyUserTransfer()->getCompanyUserTransfer()->getFkCompany());

        $companyUsers = $this->companyUserClient->getCompanyUserCollection($companyUserCriteriaFilterTransfer)->getCompanyUsers();
        return $this->mapCompanyUsersToOptions($companyUsers, $customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer[]|\ArrayObject $companyUsers
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    protected function mapCompanyUsersToOptions(ArrayObject $companyUsers, CustomerTransfer $customerTransfer): array
    {
        $companyUsersOptions = [];
        foreach ($companyUsers as $companyUser) {
            if ($customerTransfer->getCompanyUserTransfer()->getIdCompanyUser() !== $companyUser->getIdCompanyUser()) {
                $companyUsersOptions[$companyUser->getIdCompanyUser()]
                    = $companyUser->getCustomer()->getFirstName() . ' ' . $companyUser->getCustomer()->getLastName();
            }
        }

        return $companyUsersOptions;
    }
}
