<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;
use SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Provider\CompanyUserInvitationPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
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
        $invitationId = (int)$request->get(CompanyUserInvitationPageConstants::INVITATION_ID);
        $companyUserInvitationTransfer = (new CompanyUserInvitationTransfer())
            ->setIdCompanyUserInvitation($invitationId);

        $companyUserInvitationSendResultTransfer = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->sendCompanyUserInvitation($companyUserInvitationTransfer);

        if ($companyUserInvitationSendResultTransfer->getSuccess()) {
            $this->addSuccessMessage('company.user.invitation.sent.success.message');
            return $this->redirectResponseInternal(CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW);
        }

        $this->addErrorMessage('company.user.invitation.sent.error.message');
        return $this->redirectResponseInternal(CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendCompanyUserInvitationsAction(Request $request)
    {
        $companyUserTransfer = $this->getFactory()->getCustomerClient()->getCustomer()->getCompanyUserTransfer();
        $companyUserInvitationSendBatchResultTransfer = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->sendCompanyUserInvitations($companyUserTransfer);

        if (!$companyUserInvitationSendBatchResultTransfer->getInvitationsTotal()) {
            $this->addInfoMessage('company.user.invitation.sent.all.nome.found.message');
            return $this->redirectResponseInternal(CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW);
        }

        if (!$companyUserInvitationSendBatchResultTransfer->getInvitationsFailed()) {
            $this->addSuccessMessage('company.user.invitation.sent.all.success.message');
            return $this->redirectResponseInternal(CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW);
        }

        $this->addErrorMessage('company.user.invitation.sent.all.error.message');
        return $this->redirectResponseInternal(CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW);
    }
}
