<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Router\CompanyUserInvitationPageRouteProviderPlugin` instead.
 */
class CompanyUserInvitationPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_OVERVIEW = 'company/user-invitation';
    public const ROUTE_GET_IMPORT_ERRORS = 'company/user-invitation/get-import-errors';

    public const ROUTE_INVITATION_SEND = 'company/user-invitation/send';
    public const ROUTE_INVITATION_SEND_ALL = 'company/user-invitation/send-all';

    public const ROUTE_INVITATION_RESEND = 'company/user-invitation/resend';
    public const ROUTE_INVITATION_RESEND_CONFIRM = 'company/user-invitation/resend/confirm';

    /**
     * @see \Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants::ROUTE_INVITATION_ACCEPT
     */
    public const ROUTE_INVITATION_ACCEPT = 'invitation/accept';

    public const ROUTE_INVITATION_DELETE = 'company/user-invitation/delete';
    public const ROUTE_INVITATION_DELETE_CONFIRM = 'company/user-invitation/delete/confirm';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addUserInvitationRoute()
            ->addUserInvitationErrorsRoute()
            ->addUserInvitationSendRoute()
            ->addUserInvitationSendAllRoute()
            ->addUserInvitationResendRoute()
            ->addUserInvitationResendConfirmRoute()
            ->addUserInvitationDeleteRoute()
            ->addUserInvitationDeleteConfirmRoute()
            ->addUserInvitationAcceptRoute();
    }

    /**
     * @return $this
     */
    protected function addUserInvitationRoute()
    {
        $this->createController('/{companyUserInvitation}', static::ROUTE_OVERVIEW, 'CompanyUserInvitationPage', 'Import')
            ->assert('companyUserInvitation', $this->getAllowedLocalesPattern() . 'company/user-invitation|company/user-invitation')
            ->value('companyUserInvitation', 'company/user-invitation');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUserInvitationErrorsRoute()
    {
        $this->createController('/{companyUserInvitation}/get-import-errors', static::ROUTE_GET_IMPORT_ERRORS, 'CompanyUserInvitationPage', 'Import', 'getErrors')
            ->assert('companyUserInvitation', $this->getAllowedLocalesPattern() . 'company/user-invitation|company/user-invitation')
            ->value('companyUserInvitation', 'company/user-invitation');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUserInvitationSendRoute()
    {
        $this->createController('/{companyUserInvitation}/send', static::ROUTE_INVITATION_SEND, 'CompanyUserInvitationPage', 'Send', 'sendCompanyUserInvitation')
            ->assert('companyUserInvitation', $this->getAllowedLocalesPattern() . 'company/user-invitation|company/user-invitation')
            ->value('companyUserInvitation', 'company/user-invitation');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUserInvitationSendAllRoute()
    {
        $this->createController('/{companyUserInvitation}/send-all', static::ROUTE_INVITATION_SEND_ALL, 'CompanyUserInvitationPage', 'Send', 'sendCompanyUserInvitations')
            ->assert('companyUserInvitation', $this->getAllowedLocalesPattern() . 'company/user-invitation|company/user-invitation')
            ->value('companyUserInvitation', 'company/user-invitation');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUserInvitationResendRoute()
    {
        $this->createController('/{companyUserInvitation}/resend', static::ROUTE_INVITATION_RESEND, 'CompanyUserInvitationPage', 'Resend')
            ->assert('companyUserInvitation', $this->getAllowedLocalesPattern() . 'company/user-invitation|company/user-invitation')
            ->value('companyUserInvitation', 'company/user-invitation');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUserInvitationResendConfirmRoute()
    {
        $this->createController('/{companyUserInvitation}/resend/confirm', static::ROUTE_INVITATION_RESEND_CONFIRM, 'CompanyUserInvitationPage', 'Resend', 'confirm')
            ->assert('companyUserInvitation', $this->getAllowedLocalesPattern() . 'company/user-invitation|company/user-invitation')
            ->value('companyUserInvitation', 'company/user-invitation');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUserInvitationDeleteRoute()
    {
        $this->createController('/{companyUserInvitation}/delete', static::ROUTE_INVITATION_DELETE, 'CompanyUserInvitationPage', 'Delete')
            ->assert('companyUserInvitation', $this->getAllowedLocalesPattern() . 'company/user-invitation|company/user-invitation')
            ->value('companyUserInvitation', 'company/user-invitation');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUserInvitationDeleteConfirmRoute()
    {
        $this->createController('/{companyUserInvitation}/delete/confirm', static::ROUTE_INVITATION_DELETE_CONFIRM, 'CompanyUserInvitationPage', 'Delete', 'confirm')
            ->assert('companyUserInvitation', $this->getAllowedLocalesPattern() . 'company/user-invitation|company/user-invitation')
            ->value('companyUserInvitation', 'company/user-invitation');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addUserInvitationAcceptRoute()
    {
        $this->createController('/{invitation}/accept', static::ROUTE_INVITATION_ACCEPT, 'CompanyUserInvitationPage', 'Accept')
            ->assert('invitation', $this->getAllowedLocalesPattern() . 'invitation|invitation')
            ->value('invitation', 'invitation');

        return $this;
    }
}
