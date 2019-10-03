<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConfig;
use SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class AcceptController extends AbstractController
{
    public const COMPANY_USER_WITH_PERMISSIONS_REQUIRED = false;

    /**
     * @see \SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider::ROUTE_REGISTER
     */
    protected const REDIRECT_URL = 'register';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $invitationHash = $request->get(CompanyUserInvitationConfig::INVITATION_HASH);
        $companyUserInvitationTransfer = (new CompanyUserInvitationTransfer())
            ->setHash($invitationHash);

        $companyUserInvitationTransfer = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->getCompanyUserInvitationByHash($companyUserInvitationTransfer);

        if (!$companyUserInvitationTransfer->getIdCompanyUserInvitation()) {
            return $this->redirectToRouteWithErrorMessage(
                static::REDIRECT_URL,
                'company.user.invitation.invalid'
            );
        }

        if ($companyUserInvitationTransfer->getCompanyUserInvitationStatusKey()
            === CompanyUserInvitationConfig::INVITATION_STATUS_DELETED
        ) {
            return $this->redirectToRouteWithErrorMessage(
                static::REDIRECT_URL,
                'company.user.invitation.expired'
            );
        }

        if ($companyUserInvitationTransfer->getCompanyUserInvitationStatusKey()
            === CompanyUserInvitationConfig::INVITATION_STATUS_ACCEPTED
        ) {
            return $this->redirectToRouteWithErrorMessage(
                static::REDIRECT_URL,
                'company.user.invitation.accepted'
            );
        }

        $this->getFactory()->getSessionClient()->set(CompanyUserInvitationPageConfig::INVITATION_SESSION_ID, $invitationHash);

        return $this->redirectToRoute(static::REDIRECT_URL);
    }
}
