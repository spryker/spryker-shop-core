<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyUser;

use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;

class CompanyUserFinder implements CompanyUserFinderInterface
{
    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface $companyUserClient
     */
    public function __construct(
        CompanyPageToCustomerClientInterface $customerClient,
        CompanyPageToCompanyUserClientInterface $companyUserClient
    ) {
        $this->customerClient = $customerClient;
        $this->companyUserClient = $companyUserClient;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCurrentCompanyUser(): ?CompanyUserTransfer
    {
        $currentCustomerTransfer = $this->customerClient->getCustomer();

        if (!$currentCustomerTransfer) {
            return null;
        }

        $companyUserResponseTransfer = $this->companyUserClient->findCompanyUserByCustomerId($currentCustomerTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $companyUserResponseTransfer->getCompanyUser();
    }
}
