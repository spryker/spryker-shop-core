<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;
use SprykerShop\Yves\CompanyPage\Form\FormFactory;
use SprykerShop\Yves\CustomerPage\Plugin\AuthenticationHandler;

class CompanyPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyPage\Form\FormFactory
     */
    public function createCompanyFormFactory(): FormFactory
    {
        return new FormFactory();
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface
     */
    public function getCompanyRoleClient(): CompanyPageToCompanyRoleClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_ROLE);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyClientInterface
     */
    public function getCompanyClient(): CompanyPageToCompanyClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface
     */
    public function getCompanyBusinessUnitClient(): CompanyPageToCompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return array
     */
    public function getCompanyOverviewWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::PLUGIN_COMPANY_OVERVIEW_WIDGETS);
    }

    /**
     * @return \SprykerShop\Yves\CustomerPage\Plugin\AuthenticationHandler
     */
    public function getAuthenticationHandlerPlugin(): AuthenticationHandler
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::PLUGIN_AUTHENTICATION_HANDLER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface
     */
    public function getCompanyUnitAddressClient()
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_UNIT_ADDRESS);
    }
}
