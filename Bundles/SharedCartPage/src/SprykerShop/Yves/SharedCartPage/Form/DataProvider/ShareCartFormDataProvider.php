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
use Generated\Shared\Transfer\CustomerTransfer;
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
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return array
     */
    public function getOptions(?CustomerTransfer $customerTransfer = null): array
    {
        return [
            ShareCartForm::OPTION_CUSTOMERS => $this->getCustomerListData($customerTransfer),
            ShareCartForm::OPTION_PERMISSION_GROUPS => $this->getQuotePermissionGroups(),
        ];
    }

    /**
     * @param int $idQuote
     * @param \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[]|null $quoteShareDetails
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    public function getData($idQuote, ?ArrayObject $quoteShareDetails = null): ShareCartRequestTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();
        $customerTransfer->requireCompanyUserTransfer();
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        $formShareDetails = $quoteShareDetails ?: new ArrayObject();
        $formShareDetails = $this->hydrateCompanyUsersShareDetails($formShareDetails);

        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->setIdQuote($idQuote)
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setShareDetails($formShareDetails);

        return $shareCartRequestTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[] $quoteShareDetails
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[]
     */
    protected function hydrateCompanyUsersShareDetails(ArrayObject $quoteShareDetails): ArrayObject
    {
        $customer = $this->customerClient->getCustomer();
        $companyBusinessUnitTransfer = $customer
            ->requireCompanyUserTransfer()->getCompanyUserTransfer()->getCompanyBusinessUnit();
        $businessUnitCompanyUserList = $this->getBusinessUnitCustomers($companyBusinessUnitTransfer);

        $hydratedFormShareDetails = new ArrayObject();
        foreach ($businessUnitCompanyUserList as $companyUserTransfer) {
            if ($companyUserTransfer->getFkCustomer() === $customer->getIdCustomer()) {
                continue;
            }

            $hasQuoteShareDetail = false;
            $idCompanyUser = $companyUserTransfer->getIdCompanyUser();
            foreach ($quoteShareDetails as $quoteShareDetail) {
                if ($quoteShareDetail->getIdCompanyUser() === $idCompanyUser) {
                    $hasQuoteShareDetail = true;
                    $hydratedFormShareDetails[$idCompanyUser] = $quoteShareDetail;
                }
            }
            if (!$hasQuoteShareDetail) {
                $hydratedFormShareDetails[$idCompanyUser] = $this->createShareDetailForCompanyUser($companyUserTransfer);
            }
        }

        return $hydratedFormShareDetails;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer
     */
    protected function createShareDetailForCompanyUser(
        CompanyUserTransfer $companyUserTransfer
    ): ShareDetailTransfer {
        $companyUserTransfer->requireIdCompanyUser();
        $companyUserTransfer->requireCustomer();

        $customerTransfer = $companyUserTransfer->getCustomer();

        $customerTransfer->requireFirstName();
        $customerTransfer->requireLastName();

        return (new ShareDetailTransfer())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setCustomerName($customerTransfer->getLastName() . ' ' . $customerTransfer->getFirstName());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return array
     */
    public function getCompanyUserNames(?CustomerTransfer $customerTransfer = null): array
    {
        return $this->getCustomerListData($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return array
     */
    protected function getCustomerListData(?CustomerTransfer $customerTransfer = null): array
    {
        $customer = $customerTransfer ?? $this->customerClient->getCustomer();

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

        $noAccessPermissionGroupName = static::GLOSSARY_KEY_PERMISSIONS . static::PERMISSION_NO_ACCESS;
        $quotePermissionGroupData = [$noAccessPermissionGroupName => null];
        foreach ($quotePermissionGroupResponseTransfer->getQuotePermissionGroups() as $quotePermissionGroupTransfer) {
            $quotePermissionGroupKey = static::GLOSSARY_KEY_PERMISSIONS . $quotePermissionGroupTransfer->getName();
            $quotePermissionGroupData[$quotePermissionGroupKey] = $quotePermissionGroupTransfer->getIdQuotePermissionGroup();
        }

        return $quotePermissionGroupData;
    }
}
