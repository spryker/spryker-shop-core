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

class CompanyUserPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        return $container;
    }
}
