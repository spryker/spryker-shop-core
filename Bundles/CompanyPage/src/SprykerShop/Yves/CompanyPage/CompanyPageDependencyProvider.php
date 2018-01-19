<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Plugin\AuthenticationHandlerPluginBridge;
use SprykerShop\Yves\CustomerPage\Plugin\AuthenticationHandler;

class CompanyPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMPANY = 'CLIENT_COMPANY';
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const PLUGIN_AUTHENTICATION_HANDLER = 'PLUGIN_AUTHENTICATION_HANDLER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCompanyClient($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addAuthenticationHandlerPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyClient(Container $container): Container
    {
        $container[self::CLIENT_COMPANY] = function (Container $container) {
            return new CompanyPageToCompanyClientBridge($container->getLocator()->company()->client());
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
            return new CompanyPageToCompanyUserClientBridge($container->getLocator()->companyUser()->client());
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
            return new CompanyPageToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAuthenticationHandlerPlugin(Container $container): Container
    {
        $container[self::PLUGIN_AUTHENTICATION_HANDLER] = function () {
            return new AuthenticationHandlerPluginBridge(new AuthenticationHandler());
        };

        return $container;
    }
}
