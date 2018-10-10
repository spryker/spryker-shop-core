<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCompanyUnitAddressClientBridge;
use SprykerShop\Yves\CompanyWidget\Dependency\Client\CompanyWidgetToCustomerClientBridge;

class CompanyWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_COMPANY_BUSINESS_UNIT_ADDRESS = 'CLIENT_COMPANY_BUSINESS_UNIT_ADDRESS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->provideCustomerClient($container);
        $container = $this->addCompanyBusinessUnitAddress($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideCustomerClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new CompanyWidgetToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyBusinessUnitAddress(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_BUSINESS_UNIT_ADDRESS] = function (Container $container) {
            return new CompanyWidgetToCompanyUnitAddressClientBridge(
                $container->getLocator()->companyUnitAddress()->client()
            );
        };

        return $container;
    }
}
