<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Model\Mapper;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Iterator;
use SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageConfig;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCustomerClientInterface;

class InvitationMapper implements InvitationMapperInterface
{
    /**
     * @var \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCustomerClientInterface $customerClient
     */
    public function __construct(
        CompanyUserInvitationPageToCustomerClientInterface $customerClient
    ) {
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Iterator $invitations
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer
     */
    public function mapInvitations(Iterator $invitations): CompanyUserInvitationCollectionTransfer
    {
        $idCompanyUser = $this->customerClient->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser();
        $companyUserInvitationCollectionTransfer = new CompanyUserInvitationCollectionTransfer();
        foreach ($invitations as $invitation) {
            $companyUserInvitationTransfer = $this->getCompanyUserInvitationTransfer($invitation);
            $companyUserInvitationTransfer->setFkCompanyUser($idCompanyUser);
            $companyUserInvitationCollectionTransfer->addCompanyUserInvitation($companyUserInvitationTransfer);
        }

        return $companyUserInvitationCollectionTransfer;
    }

    /**
     * @param array $record
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    protected function getCompanyUserInvitationTransfer(array $record): CompanyUserInvitationTransfer
    {
        return (new CompanyUserInvitationTransfer())
            ->setFirstName($record[CompanyUserInvitationPageConfig::COLUMN_FIRST_NAME])
            ->setLastName($record[CompanyUserInvitationPageConfig::COLUMN_LAST_NAME])
            ->setEmail($record[CompanyUserInvitationPageConfig::COLUMN_EMAIL])
            ->setCompanyBusinessUnitName($record[CompanyUserInvitationPageConfig::COLUMN_BUSINESS_UNIT]);
    }
}
