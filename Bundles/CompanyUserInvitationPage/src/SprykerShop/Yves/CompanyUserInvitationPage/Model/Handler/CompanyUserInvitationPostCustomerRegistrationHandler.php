<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Model\Handler;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserClientInterface;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserInvitationClientInterface;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToSessionClientInterface;

class CompanyUserInvitationPostCustomerRegistrationHandler implements CompanyUserInvitationPostCustomerRegistrationHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToSessionClientInterface
     */
    private $sessionClient;

    /**
     * @var \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserInvitationClientInterface
     */
    private $companyUserInvitationClient;

    /**
     * @var \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserClientInterface
     */
    private $companyUserClient;

    /**
     * @param \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToSessionClientInterface $sessionClient
     * @param \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserInvitationClientInterface $companyUserInvitationClient
     * @param \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserClientInterface $companyUserClient
     */
    public function __construct(
        CompanyUserInvitationPageToSessionClientInterface $sessionClient,
        CompanyUserInvitationPageToCompanyUserInvitationClientInterface $companyUserInvitationClient,
        CompanyUserInvitationPageToCompanyUserClientInterface $companyUserClient
    ) {
        $this->sessionClient = $sessionClient;
        $this->companyUserInvitationClient = $companyUserInvitationClient;
        $this->companyUserClient = $companyUserClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function handle(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $companyUserInvitationTransfer = $this->getCompanyUserInvitationTransfer();
        if (!$this->isValidCompanyUserInvitationTransfer($companyUserInvitationTransfer)) {
            return $customerTransfer;
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setFkCustomer($customerTransfer->getIdCustomer())
            ->setFkCompany($companyUserInvitationTransfer->getCompanyId())
            ->setFkCompanyBusinessUnit($companyUserInvitationTransfer->getFkCompanyBusinessUnit())
            ->setCustomer($customerTransfer);

        $companyUserResponseTransfer = $this->companyUserClient->updateCompanyUser($companyUserTransfer);
        if ($companyUserResponseTransfer->getIsSuccessful()) {
            $companyUserInvitationUpdateStatusRequestTransfer = (new CompanyUserInvitationUpdateStatusRequestTransfer())
                ->setInvitation($companyUserInvitationTransfer)
                ->setStatusKey(CompanyUserInvitationConstants::INVITATION_STATUS_ACCEPTED);
            $this->companyUserInvitationClient->updateCompanyUserInvitationStatus($companyUserInvitationUpdateStatusRequestTransfer);
        }

        return $customerTransfer->setCompanyUserTransfer($companyUserResponseTransfer->getCompanyUser());
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer|null
     */
    protected function getCompanyUserInvitationTransfer(): ?CompanyUserInvitationTransfer
    {
        $invitationHash = $this->sessionClient->get(CompanyUserInvitationPageConstants::INVITATION_SESSION_ID);
        $companyUserInvitationTransfer = (new CompanyUserInvitationTransfer())->setHash($invitationHash);

        return $this->companyUserInvitationClient->findCompanyUserInvitationByHash($companyUserInvitationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return bool
     */
    protected function isValidCompanyUserInvitationTransfer(CompanyUserInvitationTransfer $companyUserInvitationTransfer): bool
    {
        return $companyUserInvitationTransfer &&
            $companyUserInvitationTransfer->getCompanyUserInvitationStatusKey()
            === CompanyUserInvitationConstants::INVITATION_STATUS_PENDING;
    }
}
