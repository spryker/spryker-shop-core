<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCompanyUserClientInterface;
use SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCustomerClientInterface;
use SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToSharedCartClientInterface;
use SprykerShop\Yves\SharedCartPage\Form\ShareCartForm;

class ShareCartFormDataProvider implements ShareCartFormDataProviderInterface
{
    protected const GLOSSARY_KEY_PERMISSIONS = 'shared_cart.form.select_permissions';
    protected const PERMISSION_NO_ACCESS = 'NO_ACCESS';

    /**
     * @var \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @var \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToSharedCartClientInterface
     */
    protected $sharedCartClient;

    /**
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCompanyUserClientInterface $companyUserClient
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToSharedCartClientInterface $sharedCartClient
     */
    public function __construct(
        SharedCartPageToCustomerClientInterface $customerClient,
        SharedCartPageToCompanyUserClientInterface $companyUserClient,
        SharedCartPageToSharedCartClientInterface $sharedCartClient
    ) {
        $this->customerClient = $customerClient;
        $this->companyUserClient = $companyUserClient;
        $this->sharedCartClient = $sharedCartClient;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ShareCartForm::OPTION_CUSTOMERS => $this->getCustomerListData(),
            ShareCartForm::OPTION_PERMISSION_GROUPS => $this->getQuotePermissionGroups(),
        ];
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    public function getData($idQuote): ShareCartRequestTransfer
    {
        $shareCartRequestTransfer = new ShareCartRequestTransfer();
        $shareCartRequestTransfer->setIdQuote($idQuote);

        return $shareCartRequestTransfer;
    }

    /**
     * @return array
     */
    protected function getCustomerListData(): array
    {
        $customer = $this->customerClient->getCustomer();
        $companyBusinessUnitTransfer = $customer
            ->requireCompanyUserTransfer()->getCompanyUserTransfer()->getCompanyBusinessUnit();
        $businessUnitCompanyUserList = $this->getBusinessUnitCustomers($companyBusinessUnitTransfer);
        $customerListData = [];
        foreach ($businessUnitCompanyUserList as $companyUserTransfer) {
            if ($customer->getIdCustomer() === $companyUserTransfer->getFkCustomer()) {
                continue;
            }
            $customerTransfer = $companyUserTransfer->getCustomer();
            $customerListData[$companyUserTransfer->getIdCompanyUser()] =
                $customerTransfer->getLastName() . ' ' . $customerTransfer->getFirstName();
        }

        return $customerListData;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    protected function getBusinessUnitCustomers(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): array
    {
        $companyUserCriteriaFilterTransfer = new CompanyUserCriteriaFilterTransfer();
        $companyUserCriteriaFilterTransfer->setIdCompany($companyBusinessUnitTransfer->getFkCompany());
        $companyUserCollectionTransfer = $this->companyUserClient->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        $businessUnitCompanyUserList = [];
        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            if ($companyBusinessUnitTransfer->getIdCompanyBusinessUnit() === $companyUserTransfer->getFkCompanyBusinessUnit()) {
                $businessUnitCompanyUserList[] = $companyUserTransfer;
            }
        }

        return $businessUnitCompanyUserList;
    }

    /**
     * @return array
     */
    protected function getQuotePermissionGroups(): array
    {
        $criteriaFilterTransfer = new QuotePermissionGroupCriteriaFilterTransfer();
        $quotePermissionGroupResponseTransfer = $this->sharedCartClient->getQuotePermissionGroupList($criteriaFilterTransfer);
        if (!$quotePermissionGroupResponseTransfer->getIsSuccessful()) {
            return [];
        }
        $quotePermissionGroupData = [];
        foreach ($quotePermissionGroupResponseTransfer->getQuotePermissionGroups() as $quotePermissionGroupTransfer) {
            $quotePermissionGroupData[$quotePermissionGroupTransfer->getIdQuotePermissionGroup()]
                = 'shared_cart.share_list.permissions.' . $quotePermissionGroupTransfer->getName();
        }

        return $quotePermissionGroupData;
    }
}
