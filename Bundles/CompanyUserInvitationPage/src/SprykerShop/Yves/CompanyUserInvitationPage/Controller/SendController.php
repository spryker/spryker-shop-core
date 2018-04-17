<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;
use SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Provider\CompanyUserInvitationPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class SendController extends AbstractModuleController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendCompanyUserInvitationAction(Request $request): RedirectResponse
    {
        $invitationId = (int)$request->get(CompanyUserInvitationPageConstants::INVITATION_ID);
        $companyUserInvitationTransfer = (new CompanyUserInvitationTransfer())
            ->setIdCompanyUserInvitation($invitationId);

        $companyUserInvitationSendResultTransfer = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->sendCompanyUserInvitation($companyUserInvitationTransfer);

        if ($companyUserInvitationSendResultTransfer->getSuccess()) {
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
        $companyUserTransfer = $this->getFactory()->getCustomerClient()->getCustomer()->getCompanyUserTransfer();
        $companyUserInvitationSendBatchResultTransfer = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->sendCompanyUserInvitations($companyUserTransfer);

        if (!$companyUserInvitationSendBatchResultTransfer->getInvitationsTotal()) {
            return $this->redirectToRouteWithInfoMessage(
                CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW,
                'company.user.invitation.sent.all.nome.found.message'
            );
        }

        if (!$companyUserInvitationSendBatchResultTransfer->getInvitationsFailed()) {
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
