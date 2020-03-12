<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientInterface;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientInterface;
use SprykerShop\Yves\CompanyBusinessUnitWidget\FormExpander\OrderSearchFormExpander;
use SprykerShop\Yves\CompanyBusinessUnitWidget\FormExpander\OrderSearchFormExpanderInterface;
use SprykerShop\Yves\CompanyBusinessUnitWidget\FormHandler\OrderSearchFormHandler;
use SprykerShop\Yves\CompanyBusinessUnitWidget\FormHandler\OrderSearchFormHandlerInterface;

class CompanyBusinessUnitWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyBusinessUnitWidget\FormExpander\OrderSearchFormExpanderInterface
     */
    public function createOrderSearchFormExpander(): OrderSearchFormExpanderInterface
    {
        return new OrderSearchFormExpander(
            $this->getCompanyBusinessUnitSalesConnectorClient(),
            $this->getCustomerClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyBusinessUnitWidget\FormHandler\OrderSearchFormHandlerInterface
     */
    public function createOrderSearchFormHandler(): OrderSearchFormHandlerInterface
    {
        return new OrderSearchFormHandler(
            $this->getCustomerClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientInterface
     */
    public function getCompanyBusinessUnitSalesConnectorClient(): CompanyBusinessUnitWidgetToCompanyBusinessUnitSalesConnectorClientInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitWidgetDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT_SALES_CONNECTOR);
    }

    /**
     * @return \SprykerShop\Yves\CompanyBusinessUnitWidget\Dependency\Client\CompanyBusinessUnitWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyBusinessUnitWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitWidgetDependencyProvider::CLIENT_CUSTOMER);
    }
}
