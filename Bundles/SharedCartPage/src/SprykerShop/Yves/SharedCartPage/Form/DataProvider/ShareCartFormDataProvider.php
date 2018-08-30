<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use SprykerShop\Yves\SharedCartPage\CompanyUser\CompanyUserFinderInterface;
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
     * @var \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToSharedCartClientInterface
     */
    protected $sharedCartClient;

    /**
     * @var \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToMultiCartClientInterface
     */
    protected $multiCartClient;

    /**
     * @var \SprykerShop\Yves\SharedCartPage\CompanyUser\CompanyUserFinderInterface
     */
    protected $companyUserFinder;

    /**
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToSharedCartClientInterface $sharedCartClient
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToMultiCartClientInterface $multiCartClient
     * @param \SprykerShop\Yves\SharedCartPage\CompanyUser\CompanyUserFinderInterface $companyUserFinder
     */
    public function __construct(
        SharedCartPageToCustomerClientInterface $customerClient,
        SharedCartPageToSharedCartClientInterface $sharedCartClient,
        SharedCartPageToMultiCartClientInterface $multiCartClient,
        CompanyUserFinderInterface $companyUserFinder
    ) {
        $this->customerClient = $customerClient;
        $this->sharedCartClient = $sharedCartClient;
        $this->multiCartClient = $multiCartClient;
        $this->companyUserFinder = $companyUserFinder;
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
     * @param int $idQuote
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[]
     */
    protected function getCompanyUsersShareDetails(int $idQuote): ArrayObject
    {
        $customerTransfer = $this->customerClient->getCustomer();

        $companyBusinessUnitTransfer = $this->companyUserFinder->getCompanyBusinessUnit();
        $businessUnitCompanyUserList = $this->companyUserFinder->getBusinessUnitCustomers($companyBusinessUnitTransfer);

        $quoteTransfer = $this->multiCartClient->findQuoteById($idQuote);

        $formShareDetails = new ArrayObject();
        foreach ($businessUnitCompanyUserList as $companyUserTransfer) {
            if ($customerTransfer->getIdCustomer() === $companyUserTransfer->getFkCustomer()) {
                continue;
            }

            $formShareDetails[$companyUserTransfer->getIdCompanyUser()] = $this->findOrCreateCompanyUserShareDetailTransfer(
                $companyUserTransfer,
                $quoteTransfer
            );
        }

        return $formShareDetails;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer
     */
    protected function findOrCreateCompanyUserShareDetailTransfer(
        CompanyUserTransfer $companyUserTransfer,
        QuoteTransfer $quoteTransfer
    ): ShareDetailTransfer {
        $companyUserTransfer->requireIdCompanyUser();
        $companyUserTransfer->requireCustomer();

        $customerTransfer = $companyUserTransfer->getCustomer();

        $customerTransfer->requireFirstName();
        $customerTransfer->requireLastName();

        foreach ($quoteTransfer->getShareDetails() as $quoteShareDetail) {
            if ($quoteShareDetail->getIdCompanyUser() === $companyUserTransfer->getIdCompanyUser()) {
                return $quoteShareDetail;
            }
        }

        return (new ShareDetailTransfer())
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setCustomerName($customerTransfer->getLastName() . ' ' . $customerTransfer->getFirstName());
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
