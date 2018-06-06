<?php

namespace SprykerShop\Yves\CompanyPage\Model\CompanyUser;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerShop\Client\CompanyPage\Dependency\Client\CompanyPageToMessengerClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;
use SprykerShop\Yves\CompanyPage\Form\CompanyUserAccountForm;

class CompanyUserSaver implements CompanyUserSaverInterface
{
    protected const ERROR_COMPANY_NOT_ACTIVE = 'company_user.business_on_behalf.error.company_not_active';
    protected const ERROR_COMPANY_USER_INVALID = 'company_user.business_on_behalf.error.company_user_invalid';

    /**
     * @var \SprykerShop\Client\CompanyPage\Dependency\Client\CompanyPageToMessengerClientInterface
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
     * @param \SprykerShop\Client\CompanyPage\Dependency\Client\CompanyPageToMessengerClientInterface $messengerClient
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
        $this->businessOnBehalfClient  =$businessOnBehalfClient;
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
        $idCompanyUserSelected = $formData[CompanyUserAccountForm::FIELD_COMPANY_USER_ACCOUNT_CHOICE];

        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUser) {
            if ($this->updateCustomerInSession($companyUser, $idCompanyUserSelected, $isDefault)) {
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
    protected function updateCustomerInSession(
        CompanyUserTransfer $companyUser,
        string $idCompanyUserSelected,
        bool $isDefault = false
    ): bool {
        $customerTransfer = $this->customerClient->getCustomer();

        if (empty($idCompanyUserSelected)) {
            $customerTransfer->setCompanyUserTransfer(null);
            $this->businessOnBehalfClient->unsetDefaultCompanyUser($customerTransfer);
            $this->customerClient->setCustomer($customerTransfer);

            return true;
        }

        if (!$companyUser->getCompany()->getIsActive()) {
            $this->messengerClient->addErrorMessage(static::ERROR_COMPANY_NOT_ACTIVE);

            return false;
        }

        if ($companyUser->getIdCompanyUser() == $idCompanyUserSelected) {
            $companyUser->setIsDefault($isDefault);
            $companyUser = $this->businessOnBehalfClient->setDefaultCompanyUser($companyUser);
            $customerTransfer->setCompanyUserTransfer($companyUser);
            $this->customerClient->setCustomer($customerTransfer);

            return true;
        }

        return false;
    }
}