<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationSendRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;
use SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Provider\CompanyUserInvitationPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class SendController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendCompanyUserInvitationAction(Request $request): RedirectResponse
    {
        $invitationId = (int)$request->get(CompanyUserInvitationPageConstants::ID_COMPANY_USER_INVITATION);
        $companyUserInvitationSendRequestTransfer = (new CompanyUserInvitationSendRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitation(
                (new CompanyUserInvitationTransfer())->setIdCompanyUserInvitation($invitationId)
            );

        $companyUserInvitationSendResponseTransfer = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->sendCompanyUserInvitation($companyUserInvitationSendRequestTransfer);

        if ($companyUserInvitationSendResponseTransfer->getIsSuccess()) {
            return $this->redirectToRouteWithSuccessMessage(
                CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW,
                'company.user.invitation.sent.success.message'
            );
        }

        return $this->redirectToRouteWithErrorMessage(
            CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW,
            'company.user.invitation.sent.error.message'
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendCompanyUserInvitationsAction()
    {
        $companyUserInvitationSendBatchResponseTransfer = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->sendCompanyUserInvitations($this->companyUserTransfer);

        if (!$companyUserInvitationSendBatchResponseTransfer->getIsSuccess()) {
            return $this->redirectToRouteWithErrorMessage(
                CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW,
                'company.user.invitation.sent.all.error.message'
            );
        }

        if (!$companyUserInvitationSendBatchResponseTransfer->getInvitationsTotal()) {
            return $this->redirectToRouteWithInfoMessage(
                CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW,
                'company.user.invitation.sent.all.none.found.message'
            );
        }

        if (!$companyUserInvitationSendBatchResponseTransfer->getInvitationsFailed()) {
            return $this->redirectToRouteWithSuccessMessage(
                CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW,
                'company.user.invitation.sent.all.success.message'
            );
        }

        return $this->redirectToRouteWithErrorMessage(
            CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW,
            'company.user.invitation.sent.all.error.message'
        );
    }
}
