<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserClientBridge;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserInvitationClientBridge;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCustomerClientBridge;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToSessionClientBridge;

class CompanyUserInvitationPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMPANY_USER_INVITATION = 'CLIENT_COMPANY_USER_INVITATION';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_SESSION = 'CLIENT_SESSION';
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addSessionClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addCompanyUserInvitationClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container[self::CLIENT_SESSION] = function (Container $container) {
            return new CompanyUserInvitationPageToSessionClientBridge(
                $container->getLocator()->session()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[self::CLIENT_CUSTOMER] = function (Container $container) {
            return new CompanyUserInvitationPageToCustomerClientBridge(
                $container->getLocator()->customer()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUserClient(Container $container): Container
    {
        $container[self::CLIENT_COMPANY_USER] = function (Container $container) {
            return new CompanyUserInvitationPageToCompanyUserClientBridge(
                $container->getLocator()->companyUser()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUserInvitationClient(Container $container): Container
    {
        $container[self::CLIENT_COMPANY_USER_INVITATION] = function (Container $container) {
            return new CompanyUserInvitationPageToCompanyUserInvitationClientBridge(
                $container->getLocator()->companyUserInvitation()->client()
            );
        };

        return $container;
    }
}
