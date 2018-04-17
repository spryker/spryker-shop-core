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
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class AcceptController extends AbstractModuleController
{
    public const COMPANY_USER_REQUIRED = false;

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
            ->getCompanyUserInvitationByHash($companyUserInvitationTransfer);

        if (!$companyUserInvitationTransfer->getIdCompanyUserInvitation()) {
            return $this->redirectToRouteWithErrorMessage(
                CustomerPageControllerProvider::ROUTE_REGISTER,
                'company.user.invitation.invalid'
            );
        }

        if ($companyUserInvitationTransfer->getCompanyUserInvitationStatusKey() === CompanyUserInvitationConstants::INVITATION_STATUS_DELETED) {
            return $this->redirectToRouteWithErrorMessage(
                CustomerPageControllerProvider::ROUTE_REGISTER,
                'company.user.invitation.expired'
            );
        }

        if ($companyUserInvitationTransfer->getCompanyUserInvitationStatusKey() === CompanyUserInvitationConstants::INVITATION_STATUS_ACCEPTED) {
            return $this->redirectToRouteWithErrorMessage(
                CustomerPageControllerProvider::ROUTE_REGISTER,
                'company.user.invitation.accepted'
            );
        }

        $this->getFactory()->getSessionClient()->set(CompanyUserInvitationPageConstants::INVITATION_SESSION_ID, $invitationHash);

        return $this->redirectToRoute(CustomerPageControllerProvider::ROUTE_REGISTER);
    }
}
