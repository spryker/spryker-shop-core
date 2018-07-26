<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyRoleClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUserClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToMessengerClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToPermissionClientInterface;
use SprykerShop\Yves\CompanyPage\Form\FormFactory;
use SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitTreeBuilder;
use SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitTreeBuilderInterface;
use SprykerShop\Yves\CompanyPage\Model\CompanyUser\CompanyUserSaver;
use SprykerShop\Yves\CompanyPage\Model\CompanyUser\CompanyUserSaverInterface;

class CompanyPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyPage\Form\FormFactory
     */
    public function createCompanyPageFormFactory(): FormFactory
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
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface
     */
    public function getCompanyUnitAddressClient(): CompanyPageToCompanyUnitAddressClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_COMPANY_UNIT_ADDRESS);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToPermissionClientInterface
     */
    public function getPermissionClient(): CompanyPageToPermissionClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_PERMISSION);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToBusinessOnBehalfClientInterface
     */
    public function getBusinessOnBehalfClient(): CompanyPageToBusinessOnBehalfClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_BUSINESS_ON_BEHALF);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToMessengerClientInterface
     */
    public function getMessengerClient(): CompanyPageToMessengerClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_MESSENGER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Model\CompanyUser\CompanyUserSaverInterface
     */
    public function createCompanyUserSaver(): CompanyUserSaverInterface
    {
        return new CompanyUserSaver(
            $this->getMessengerClient(),
            $this->getCustomerClient(),
            $this->getBusinessOnBehalfClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitTreeBuilderInterface
     */
    public function createCompanyBusinessUnitTreeBuilder(): CompanyBusinessUnitTreeBuilderInterface
    {
        return new CompanyBusinessUnitTreeBuilder(
            $this->getCustomerClient(),
            $this->getCompanyBusinessUnitClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface
     */
    public function getGlossaryClient(): CompanyPageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_GLOSSARY);
    }
}
