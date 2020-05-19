<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Application;
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
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToStoreClientInterface;
use SprykerShop\Yves\CompanyPage\Expander\CompanyBusinessUnitOrderSearchFormExpander;
use SprykerShop\Yves\CompanyPage\Expander\CompanyBusinessUnitOrderSearchFormExpanderInterface;
use SprykerShop\Yves\CompanyPage\Expander\CompanyUnitAddressExpander;
use SprykerShop\Yves\CompanyPage\Expander\CompanyUnitAddressExpanderInterface;
use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyBusinessUnitOrderSearchFormDataProvider;
use SprykerShop\Yves\CompanyPage\Form\FormFactory;
use SprykerShop\Yves\CompanyPage\FormHandler\OrderSearchFormHandler;
use SprykerShop\Yves\CompanyPage\FormHandler\OrderSearchFormHandlerInterface;
use SprykerShop\Yves\CompanyPage\Mapper\CompanyUnitMapper;
use SprykerShop\Yves\CompanyPage\Mapper\CompanyUnitMapperInterface;
use SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitAddressReader;
use SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitAddressReaderInterface;
use SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitAddressSaver;
use SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitAddressSaverInterface;
use SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitTreeReader;
use SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitTreeReaderInterface;
use SprykerShop\Yves\CompanyPage\Model\CompanyUser\CompanyUserSaver;
use SprykerShop\Yves\CompanyPage\Model\CompanyUser\CompanyUserSaverInterface;
use SprykerShop\Yves\CompanyPage\Model\CompanyUser\CompanyUserValidator;
use SprykerShop\Yves\CompanyPage\Model\CompanyUser\CompanyUserValidatorInterface;

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
     * @return \SprykerShop\Yves\CompanyPage\Expander\CompanyBusinessUnitOrderSearchFormExpanderInterface
     */
    public function createCompanyBusinessUnitOrderSearchFormExpander(): CompanyBusinessUnitOrderSearchFormExpanderInterface
    {
        return new CompanyBusinessUnitOrderSearchFormExpander(
            $this->createCompanyBusinessUnitOrderSearchFormDataProvider()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\FormHandler\OrderSearchFormHandlerInterface
     */
    public function createOrderSearchFormHandler(): OrderSearchFormHandlerInterface
    {
        return new OrderSearchFormHandler($this->getCustomerClient());
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyBusinessUnitOrderSearchFormDataProvider
     */
    public function createCompanyBusinessUnitOrderSearchFormDataProvider(): CompanyBusinessUnitOrderSearchFormDataProvider
    {
        return new CompanyBusinessUnitOrderSearchFormDataProvider(
            $this->getCustomerClient(),
            $this->getCompanyBusinessUnitClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToStoreClientInterface
     */
    public function getStoreClient(): CompanyPageToStoreClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_STORE);
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
     * @return \SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitTreeReaderInterface
     */
    public function createCompanyBusinessUnitTreeBuilder(): CompanyBusinessUnitTreeReaderInterface
    {
        return new CompanyBusinessUnitTreeReader(
            $this->getCustomerClient(),
            $this->getCompanyBusinessUnitClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): CompanyPageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(CompanyPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitAddressSaverInterface
     */
    public function createCompanyBusinessAddressSaver(): CompanyBusinessUnitAddressSaverInterface
    {
        return new CompanyBusinessUnitAddressSaver(
            $this->getCompanyUnitAddressClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit\CompanyBusinessUnitAddressReaderInterface
     */
    public function createCompanyBusinessUnitAddressReader(): CompanyBusinessUnitAddressReaderInterface
    {
        return new CompanyBusinessUnitAddressReader(
            $this->getCompanyUnitAddressClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Model\CompanyUser\CompanyUserValidatorInterface
     */
    public function createCompanyUserValidator(): CompanyUserValidatorInterface
    {
        return new CompanyUserValidator();
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Mapper\CompanyUnitMapperInterface
     */
    public function createCompanyUnitMapper(): CompanyUnitMapperInterface
    {
        return new CompanyUnitMapper();
    }

    /**
     * @return \SprykerShop\Yves\CompanyPage\Expander\CompanyUnitAddressExpanderInterface
     */
    public function createCompanyUnitAddressExpander(): CompanyUnitAddressExpanderInterface
    {
        return new CompanyUnitAddressExpander($this->createCompanyUnitMapper());
    }
}
