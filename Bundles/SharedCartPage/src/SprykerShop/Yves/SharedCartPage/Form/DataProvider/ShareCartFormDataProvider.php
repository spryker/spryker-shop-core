<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCompanyUserClientInterface;
use SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCustomerClientInterface;
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
            ShareCartForm::OPTION_PERMISSION_GROUPS => $this->getQuotePermissionGroups(),
        ];
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    public function getData(int $idQuote): ShareCartRequestTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();
        $customerTransfer->requireCompanyUserTransfer();
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        $formShareDetails = $this->getCompanyUsersShareDetails($idQuote);

        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->setIdQuote($idQuote)
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setShareDetails($formShareDetails);

        return $shareCartRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    protected function updateShareDetails(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartRequestTransfer
    {
        $quoteResponseTransfer = $this->sharedCartClient->updateQuotePermissions($shareCartRequestTransfer);
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $shareCartRequestTransfer->setShareDetails(
                $quoteResponseTransfer->getQuoteTransfer()->getShareDetails()
            );
        }

        return $shareCartRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer
     */
    protected function createCompanyUserShareDetailTransfer(CompanyUserTransfer $companyUserTransfer, int $idQuote): ShareDetailTransfer
    {
        $companyUserTransfer->requireIdCompanyUser();
        $companyUserTransfer->requireCustomer();

        $customerTransfer = $companyUserTransfer->getCustomer();

        $customerTransfer->requireFirstName();
        $customerTransfer->requireLastName();

        return (new ShareDetailTransfer())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setIdQuoteCompanyUser($idQuote)
            ->setCustomerName($customerTransfer->getLastName() . ' ' . $customerTransfer->getFirstName());
    }

    /**
     * @param int $idQuote
     *
     * @return \ArrayObject
     */
    protected function getCompanyUsersShareDetails(int $idQuote): ArrayObject
    {
        $currentCustomerTransfer = $this->customerClient->getCustomer();
        $currentCustomerTransfer->requireCompanyUserTransfer();
        $companyBusinessUnitTransfer = $currentCustomerTransfer->getCompanyUserTransfer()
            ->getCompanyBusinessUnit();

        $businessUnitCompanyUserList = $this->getBusinessUnitCustomers($companyBusinessUnitTransfer);

        $customerListData = new ArrayObject();
        foreach ($businessUnitCompanyUserList as $companyUserTransfer) {
            if ($currentCustomerTransfer->getIdCustomer() === $companyUserTransfer->getFkCustomer()) {
                continue;
            }

            $customerListData[$companyUserTransfer->getIdCompanyUser()] = $this->createCompanyUserShareDetailTransfer(
                $companyUserTransfer,
                $idQuote
            );
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

        $noAccessPermissionGroupName = static::GLOSSARY_KEY_PERMISSIONS . static::PERMISSION_NO_ACCESS;

        $quotePermissionGroupData = [$noAccessPermissionGroupName => null];
        foreach ($quotePermissionGroupResponseTransfer->getQuotePermissionGroups() as $quotePermissionGroupTransfer) {
            $quotePermissionGroupKey = static::GLOSSARY_KEY_PERMISSIONS . $quotePermissionGroupTransfer->getName();
            $quotePermissionGroupData[$quotePermissionGroupKey] = $quotePermissionGroupTransfer;
        }

        return $quotePermissionGroupData;
    }
}
