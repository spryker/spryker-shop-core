<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCompanyUserClientInterface;
use SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCustomerClientInterface;
use SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToMultiCartClientInterface;
use SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToSharedCartClientInterface;
use SprykerShop\Yves\SharedCartPage\Form\ShareCartForm;

class ShareCartFormDataProvider implements ShareCartFormDataProviderInterface
{
    protected const GLOSSARY_KEY_PERMISSIONS = 'shared_cart.share_list.permissions.';
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
     * @var \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToMultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCompanyUserClientInterface $companyUserClient
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToSharedCartClientInterface $sharedCartClient
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToMultiCartClientInterface $multiCartClient
     */
    public function __construct(
        SharedCartPageToCustomerClientInterface $customerClient,
        SharedCartPageToCompanyUserClientInterface $companyUserClient,
        SharedCartPageToSharedCartClientInterface $sharedCartClient,
        SharedCartPageToMultiCartClientInterface $multiCartClient
    ) {
        $this->customerClient = $customerClient;
        $this->companyUserClient = $companyUserClient;
        $this->sharedCartClient = $sharedCartClient;
        $this->multiCartClient = $multiCartClient;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
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
        $shareCartRequestTransfer->setIdQuote($idQuote)
            ->setShareDetails($this->getShareDetails($idQuote));

        return $shareCartRequestTransfer;
    }

    /**
     * @param int $idQuote
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[]
     */
    protected function getShareDetails(int $idQuote): ArrayObject
    {
        $quoteTransfer = $this->multiCartClient->findQuoteById($idQuote);
        $shareDetailTransfers = $this->sharedCartClient->getShareDetailsByIdQuoteAction($quoteTransfer)
            ->getShareDetails();
        $indexedSharedCompanyUsers = $this->indexExistingCompanyUsers($shareDetailTransfers);

        foreach ($this->getCustomerListData() as $idCompanyUser => $companyUserName) {
            if (!in_array($idCompanyUser, $indexedSharedCompanyUsers)) {
                $shareDetailTransfers->append(
                    (new ShareDetailTransfer())
                        ->setIdCompanyUser($idCompanyUser)
                        ->setCustomerName($companyUserName)
                );
            }
        }

        return $shareDetailTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[] $shareDetailTransfers
     *
     * @return int[]
     */
    protected function indexExistingCompanyUsers(ArrayObject $shareDetailTransfers): array
    {
        $indexedExistingCompanyUsers = [];
        foreach ($shareDetailTransfers as $shareDetailTransfer) {
            $indexedExistingCompanyUsers[] = $shareDetailTransfer->getIdCompanyUser();
        }

        return $indexedExistingCompanyUsers;
    }

    /**
     * @return array
     */
    protected function getCustomerListData(): array
    {
        $customer = $this->customerClient->getCustomer();
        $companyBusinessUnitTransfer = $customer->requireCompanyUserTransfer()
            ->getCompanyUserTransfer()
            ->getCompanyBusinessUnit();
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
        $companyUserCriteriaFilterTransfer->setIsActive(true);
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

        $noAccessPermissionGroupName = static::GLOSSARY_KEY_PERMISSIONS . static::PERMISSION_NO_ACCESS;
        $quotePermissionGroupData = [$noAccessPermissionGroupName => null];
        foreach ($quotePermissionGroupResponseTransfer->getQuotePermissionGroups() as $quotePermissionGroupTransfer) {
            $quotePermissionGroupKey = static::GLOSSARY_KEY_PERMISSIONS . $quotePermissionGroupTransfer->getName();
            $quotePermissionGroupData[$quotePermissionGroupKey] = $quotePermissionGroupTransfer->getIdQuotePermissionGroup();
        }

        return $quotePermissionGroupData;
    }
}
