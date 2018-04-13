<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;
use SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Provider\CompanyUserInvitationPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class DeleteController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        return $this->view([
            'id' => (int)$request->get(CompanyUserInvitationPageConstants::INVITATION_ID),
        ], [], '@CompanyUserInvitationPage/views/invitation-delete/invitation-delete.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request): RedirectResponse
    {
        $invitationId = (int)$request->get(CompanyUserInvitationPageConstants::INVITATION_ID);
        $companyUserInvitationTransfer = (new CompanyUserInvitationTransfer())
            ->setIdCompanyUserInvitation($invitationId);

        $companyUserInvitationUpdateStatusRequestTransfer = (new CompanyUserInvitationUpdateStatusRequestTransfer())
            ->setInvitation($companyUserInvitationTransfer)
            ->setStatusKey(CompanyUserInvitationConstants::INVITATION_STATUS_DELETED);

        $companyUserInvitationUpdateStatusResultTransfer = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->updateCompanyUserInvitationStatus($companyUserInvitationUpdateStatusRequestTransfer);

        if ($companyUserInvitationUpdateStatusResultTransfer->getSuccess()) {
            $this->addSuccessMessage('company.user.invitation.deleted.success.message');
            return $this->redirectResponseInternal(CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW);
        }

        $this->addErrorMessage('company.user.invitation.deleted.error.message');
        return $this->redirectResponseInternal(CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW);
    }
}
