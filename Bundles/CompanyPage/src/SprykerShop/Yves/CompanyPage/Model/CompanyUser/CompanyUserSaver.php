<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyUser;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToMessengerClientInterface;
use SprykerShop\Yves\CompanyPage\Form\CompanyUserAccountSelectorForm;

class CompanyUserSaver implements CompanyUserSaverInterface
{
    protected const ERROR_COMPANY_NOT_ACTIVE = 'company_user.business_on_behalf.error.company_not_active';
    protected const ERROR_COMPANY_USER_INVALID = 'company_user.business_on_behalf.error.company_user_invalid';

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface
     */
    protected $businessOnBehalfClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToMessengerClientInterface $messengerClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface $businessOnBehalfClient
     */
    public function __construct(
        CompanyPageToMessengerClientInterface $messengerClient,
        CompanyPageToCustomerClientInterface $customerClient,
        CompanyPageToBusinessOnBehalfClientInterface $businessOnBehalfClient
    ) {
        $this->customerClient = $customerClient;
        $this->messengerClient = $messengerClient;
        $this->businessOnBehalfClient = $businessOnBehalfClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     * @param array $formData
     * @param bool $isDefault
     *
     * @return void
     */
    public function saveCompanyUser(
        CompanyUserCollectionTransfer $companyUserCollectionTransfer,
        array $formData,
        bool $isDefault = false
    ): void {
        $idCompanyUserSelected = $formData[CompanyUserAccountSelectorForm::FIELD_COMPANY_USER_ACCOUNT_CHOICE];

        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUser) {
            if ($this->saveSelectedCompanyUser($companyUser, $idCompanyUserSelected, $isDefault)) {
                return;
            }
        }
        $this->messengerClient->addErrorMessage(static::ERROR_COMPANY_USER_INVALID);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUser
     * @param string $idCompanyUserSelected
     * @param bool $isDefault
     *
     * @return bool
     */
    protected function saveSelectedCompanyUser(
        CompanyUserTransfer $companyUser,
        string $idCompanyUserSelected,
        bool $isDefault = false
    ): bool {
        if (!$companyUser->getCompany()->getIsActive()) {
            $this->messengerClient->addErrorMessage(static::ERROR_COMPANY_NOT_ACTIVE);

            return false;
        }

        $customerTransfer = $this->customerClient->getCustomer();

        if (empty($idCompanyUserSelected)) {
            $this->businessOnBehalfClient->unsetDefaultCompanyUser($companyUser->getCustomer());

            $this->updateCustomerInSession($customerTransfer, null);

            return true;
        }

        if ($companyUser->getIdCompanyUser() == $idCompanyUserSelected) {
            if (!$isDefault) {
                $this->businessOnBehalfClient->unsetDefaultCompanyUser($customerTransfer);
                $this->updateCustomerInSession($customerTransfer, $companyUser);

                return true;
            }
            $companyUserResponse = $this->businessOnBehalfClient->setDefaultCompanyUser($companyUser);
            $this->updateCustomerInSession($customerTransfer, $companyUserResponse->getCompanyUser());

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return void
     */
    protected function updateCustomerInSession(CustomerTransfer $customerTransfer, ?CompanyUserTransfer $companyUserTransfer): void
    {
        $updatedCustomerTransfer = (new CustomerTransfer())
            ->setIdCustomer($customerTransfer->getIdCustomer())
            ->setCompanyUserTransfer($companyUserTransfer);

        $updatedCustomerTransfer = $this->customerClient->getCustomerByEmail($updatedCustomerTransfer);

        $this->customerClient->setCustomer($updatedCustomerTransfer);
    }
}
