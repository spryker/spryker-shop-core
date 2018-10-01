<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Controller;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConfig;
use SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Provider\CompanyUserInvitationPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyUserInvitationPage\CompanyUserInvitationPageFactory getFactory()
 */
class DeleteController extends AbstractController
{
    protected const PARAM_ID_COMPANY_USER_INVITATION = 'idCompanyUserInvitation';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        return $this->view($this->executeIndexAction($request), [], '@CompanyUserInvitationPage/views/invitation-delete/invitation-delete.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        return [
            'idCompanyUserInvitation' => (int)$request->get(static::PARAM_ID_COMPANY_USER_INVITATION),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request): RedirectResponse
    {
        $invitationId = (int)$request->get(static::PARAM_ID_COMPANY_USER_INVITATION);
        $companyUserInvitationTransfer = (new CompanyUserInvitationTransfer())
            ->setIdCompanyUserInvitation($invitationId);

        $companyUserInvitationUpdateStatusRequestTransfer = (new CompanyUserInvitationUpdateStatusRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitation($companyUserInvitationTransfer)
            ->setStatusKey(CompanyUserInvitationConfig::INVITATION_STATUS_DELETED);

        $companyUserInvitationUpdateStatusResponseTransfer = $this->getFactory()
            ->getCompanyUserInvitationClient()
            ->updateCompanyUserInvitationStatus($companyUserInvitationUpdateStatusRequestTransfer);

        if ($companyUserInvitationUpdateStatusResponseTransfer->getIsSuccess()) {
            return $this->redirectToRouteWithSuccessMessage(
                CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW,
                'company.user.invitation.deleted.success.message'
            );
        }

        return $this->redirectToRouteWithErrorMessage(
            CompanyUserInvitationPageControllerProvider::ROUTE_OVERVIEW,
            'company.user.invitation.deleted.error.message'
        );
    }
}
