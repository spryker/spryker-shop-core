<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class AcceptController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $invitationHash = $request->get(CompanyUserInvitationPageConstants::INVITATION_HASH);
        $companyUserInvitationTransfer = (new CompanyUserInvitationTransfer())
            ->setHash($invitationHash);

        $companyUserInvitationTransfer = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->findCompanyUserInvitationByHash($companyUserInvitationTransfer);

        if (!$companyUserInvitationTransfer) {
            $this->addErrorMessage('company.user.invitation.invalid');
            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_REGISTER);
        }

        if ($companyUserInvitationTransfer->getCompanyUserInvitationStatusKey() === CompanyUserInvitationConstants::INVITATION_STATUS_DELETED) {
            $this->addErrorMessage('company.user.invitation.expired');
            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_REGISTER);
        }

        if ($companyUserInvitationTransfer->getCompanyUserInvitationStatusKey() === CompanyUserInvitationConstants::INVITATION_STATUS_ACCEPTED) {
            $this->addErrorMessage('company.user.invitation.accepted');
            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_REGISTER);
        }

        $this->getFactory()->getSessionClient()->set(CompanyUserInvitationPageConstants::INVITATION_SESSION_ID, $invitationHash);

        return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_REGISTER);
    }
}
