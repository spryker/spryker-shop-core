<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CompanyUserInvitationPageControllerProvider extends AbstractYvesControllerProvider
{
    const COMPANY_USER_INVITATION_OVERVIEW = 'company-user-invitation/overview';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{companyUserInvitation}', static::COMPANY_USER_INVITATION_OVERVIEW, 'CompanyUserInvitationPage', 'CompanyUserInvitation')
            ->assert('company-user-invitation', $allowedLocalesPattern . 'company-user-invitation|company-user-invitation')
            ->value('companyUserInvitation', 'company-user-invitation');
    }
}
