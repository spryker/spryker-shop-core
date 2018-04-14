<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Model\Mapper;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Iterator;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCustomerClientInterface;

class InvitationMapper implements InvitationMapperInterface
{
    public const COLUMN_FIRST_NAME = 'first_name';
    public const COLUMN_LAST_NAME = 'last_name';
    public const COLUMN_EMAIL = 'email';
    public const COLUMN_BUSINESS_UNIT = 'business_unit';

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
            $companyUserInvitationCollectionTransfer->addInvitation($companyUserInvitationTransfer);
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
            ->setFirstName($record[static::COLUMN_FIRST_NAME])
            ->setLastName($record[static::COLUMN_LAST_NAME])
            ->setEmail($record[static::COLUMN_EMAIL])
            ->setCompanyBusinessUnitName($record[static::COLUMN_BUSINESS_UNIT]);
    }
}
