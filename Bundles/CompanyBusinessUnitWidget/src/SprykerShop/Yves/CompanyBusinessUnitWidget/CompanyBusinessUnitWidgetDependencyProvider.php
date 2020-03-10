<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientBridge;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientBridge;

class CompanyBusinessUnitWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMPANY_BUSINESS_UNIT_SALES_CONNECTOR = 'CLIENT_COMPANY_BUSINESS_UNIT_SALES_CONNECTOR';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addCompanyBusinessUnitSalesConnectorClient($container);
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyBusinessUnitSalesConnectorClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_BUSINESS_UNIT_SALES_CONNECTOR, function (Container $container) {
            return new CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientBridge(
                $container->getLocator()->companyBusinessUnitSalesConnector()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new CompanyBusinessUnitWidgetToCustomerClientBridge(
                $container->getLocator()->customer()->client()
            );
        });

        return $container;
    }
}
