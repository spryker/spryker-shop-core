<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserInvitationClientBridge;

class CompanyUserInvitationPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_COMPANY_USER_INVITATION = 'CLIENT_COMPANY_USER_INVITATION';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container[self::CLIENT_COMPANY_USER_INVITATION] = function (Container $container) {
            return new CompanyUserInvitationPageToCompanyUserInvitationClientBridge(
                $container->getLocator()->companyUSerInvitation()->client()
            );
        };

        return $container;
    }
}
