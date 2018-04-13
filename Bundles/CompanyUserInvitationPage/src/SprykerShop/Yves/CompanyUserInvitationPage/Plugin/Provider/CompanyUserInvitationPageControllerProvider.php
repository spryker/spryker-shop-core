<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Shared\CompanyUserInvitationPage\CompanyUserInvitationPageConstants;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CompanyUserInvitationPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_OVERVIEW = 'company-user-invitation';
    public const ROUTE_GET_IMPORT_ERRORS = 'company-user-invitation/get-import-errors';

    public const ROUTE_INVITATION_SEND = 'company-user-invitation/send';
    public const ROUTE_INVITATION_SEND_ALL = 'company-user-invitation/send-all';

    public const ROUTE_INVITATION_RESEND = 'company-user-invitation/resend';
    public const ROUTE_INVITATION_RESEND_CONFIRM = 'company-user-invitation/resend/confirm';

    public const ROUTE_INVITATION_ACCEPT = CompanyUserInvitationPageConstants::ROUTE_INVITATION_ACCEPT;

    public const ROUTE_INVITATION_DELETE = 'company-user-invitation/delete';
    public const ROUTE_INVITATION_DELETE_CONFIRM = 'company-user-invitation/delete/confirm';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{companyUserInvitation}', static::ROUTE_OVERVIEW, 'CompanyUserInvitationPage', 'Import')
            ->assert('company-user-invitation', $allowedLocalesPattern . 'company-user-invitation|company-user-invitation')
            ->value('companyUserInvitation', 'company-user-invitation');

        $this->createController('/{companyUserInvitation}/get-import-errors', static::ROUTE_GET_IMPORT_ERRORS, 'CompanyUserInvitationPage', 'Import', 'getErrors')
            ->assert('company-user-invitation', $allowedLocalesPattern . 'company-user-invitation|company-user-invitation')
            ->value('companyUserInvitation', 'company-user-invitation');

        $this->createController('/{companyUserInvitation}/send', static::ROUTE_INVITATION_SEND, 'CompanyUserInvitationPage', 'Send', 'sendCompanyUserInvitation')
            ->assert('company-user-invitation', $allowedLocalesPattern . 'company-user-invitation|company-user-invitation')
            ->value('companyUserInvitation', 'company-user-invitation');

        $this->createController('/{companyUserInvitation}/send-all', static::ROUTE_INVITATION_SEND_ALL, 'CompanyUserInvitationPage', 'Send', 'sendCompanyUserInvitations')
            ->assert('company-user-invitation', $allowedLocalesPattern . 'company-user-invitation|company-user-invitation')
            ->value('companyUserInvitation', 'company-user-invitation');

        $this->createController('/{companyUserInvitation}/resend', static::ROUTE_INVITATION_RESEND, 'CompanyUserInvitationPage', 'Resend')
            ->assert('company-user-invitation', $allowedLocalesPattern . 'company-user-invitation|company-user-invitation')
            ->value('companyUserInvitation', 'company-user-invitation');

        $this->createController('/{companyUserInvitation}/resend/confirm', static::ROUTE_INVITATION_RESEND_CONFIRM, 'CompanyUserInvitationPage', 'Resend', 'confirm')
            ->assert('company-user-invitation', $allowedLocalesPattern . 'company-user-invitation|company-user-invitation')
            ->value('companyUserInvitation', 'company-user-invitation');

        $this->createController('/{companyUserInvitation}/delete', static::ROUTE_INVITATION_DELETE, 'CompanyUserInvitationPage', 'Delete')
            ->assert('company-user-invitation', $allowedLocalesPattern . 'company-user-invitation|company-user-invitation')
            ->value('companyUserInvitation', 'company-user-invitation');

        $this->createController('/{companyUserInvitation}/delete/confirm', static::ROUTE_INVITATION_DELETE_CONFIRM, 'CompanyUserInvitationPage', 'Delete', 'confirm')
            ->assert('company-user-invitation', $allowedLocalesPattern . 'company-user-invitation|company-user-invitation')
            ->value('companyUserInvitation', 'company-user-invitation');

        $this->createController('/{invitation}/accept', static::ROUTE_INVITATION_ACCEPT, 'CompanyUserInvitationPage', 'Accept')
            ->assert('invitation', $allowedLocalesPattern . 'invitation|invitation')
            ->value('invitation', 'invitation');
    }
}
