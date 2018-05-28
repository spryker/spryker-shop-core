<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToPermissionClientBridge;
use SprykerShop\Yves\CompanyPage\Dependency\Store\CompanyPageToKernelStoreBridge;
use SprykerShop\Yves\CompanyUserPage\Dependency\CompanyPageToBusinessOnBehalfClientBridge;

class CompanyUserPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_BUSINESS_ON_BEHALF = 'CLIENT_BUSINESS_ON_BEHALF';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addBusinessOnBehalfClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addBusinessOnBehalfClient(Container $container): Container
    {
        $container[static::CLIENT_BUSINESS_ON_BEHALF] = function (Container $container) {
            return new CompanyPageToBusinessOnBehalfClientBridge($container->getLocator()->businessOnBehalf()->client());
        };

        return $container;
    }
}
