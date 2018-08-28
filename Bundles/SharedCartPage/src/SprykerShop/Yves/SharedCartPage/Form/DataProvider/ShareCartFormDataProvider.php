<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Form\DataProvider;

use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use SprykerShop\Yves\SharedCartPage\CompanyUser\CompanyUserFinderInterface;
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
     * @var \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToSharedCartClientInterface
     */
    protected $sharedCartClient;

    /**
     * @var \SprykerShop\Yves\SharedCartPage\CompanyUser\CompanyUserFinderInterface
     */
    protected $companyUserFinder;

    /**
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\SharedCartPage\Dependency\Client\SharedCartPageToSharedCartClientInterface $sharedCartClient
     * @param \SprykerShop\Yves\SharedCartPage\CompanyUser\CompanyUserFinderInterface $companyUserFinder
     */
    public function __construct(
        SharedCartPageToCustomerClientInterface $customerClient,
        SharedCartPageToSharedCartClientInterface $sharedCartClient,
        CompanyUserFinderInterface $companyUserFinder
    ) {
        $this->customerClient = $customerClient;
        $this->sharedCartClient = $sharedCartClient;
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

        $formShareDetails = $this->companyUserFinder->getCompanyUsersShareDetails($idQuote);

        $shareCartRequestTransfer = (new ShareCartRequestTransfer())
            ->setIdQuote($idQuote)
            ->setIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setShareDetails($formShareDetails);

        return $shareCartRequestTransfer;
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
