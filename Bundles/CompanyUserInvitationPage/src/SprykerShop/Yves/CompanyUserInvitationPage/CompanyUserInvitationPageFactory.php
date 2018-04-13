<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserClientInterface;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserInvitationClientInterface;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCustomerClientInterface;
use SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToSessionClientInterface;
use SprykerShop\Yves\CompanyUserInvitationPage\Form\FormFactory;
use SprykerShop\Yves\CompanyUserInvitationPage\Model\Errors\ImportErrorsHandler;
use SprykerShop\Yves\CompanyUserInvitationPage\Model\Errors\ImportErrorsHandlerInterface;
use SprykerShop\Yves\CompanyUserInvitationPage\Model\Handler\CompanyUserInvitationPostCustomerRegistrationHandler;
use SprykerShop\Yves\CompanyUserInvitationPage\Model\Mapper\InvitationMapper;
use SprykerShop\Yves\CompanyUserInvitationPage\Model\Mapper\InvitationMapperInterface;
use SprykerShop\Yves\CompanyUserInvitationPage\Model\Reader\CsvInvitationReader;
use SprykerShop\Yves\CompanyUserInvitationPage\Model\Reader\InvitationReaderInterface;

class CompanyUserInvitationPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Form\FormFactory
     */
    public function createCompanyUserInvitationPageFormFactory(): FormFactory
    {
        return new FormFactory();
    }

    /**
     * @param string $importFilePath
     *
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Model\Reader\InvitationReaderInterface
     */
    public function createCsvInvitationReader(string $importFilePath): InvitationReaderInterface
    {
        return new CsvInvitationReader($importFilePath);
    }

    /**
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Model\Mapper\InvitationMapperInterface
     */
    public function createInvitationMapper(): InvitationMapperInterface
    {
        return new InvitationMapper(
            $this->getCustomerClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Model\Errors\ImportErrorsHandlerInterface
     */
    public function createImportErrorsHandler(): ImportErrorsHandlerInterface
    {
        return new ImportErrorsHandler(
            $this->getSessionClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Model\Handler\CompanyUserInvitationPostCustomerRegistrationHandlerInterface
     */
    public function createCompanyUserInvitationPostCustomerRegistrationHandler()
    {
        return new CompanyUserInvitationPostCustomerRegistrationHandler(
            $this->getSessionClient(),
            $this->getCompanyUserInvitationClient(),
            $this->getCompanyUserClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserInvitationClientInterface
     */
    public function getCompanyUserInvitationClient(): CompanyUserInvitationPageToCompanyUserInvitationClientInterface
    {
        return $this->getProvidedDependency(CompanyUserInvitationPageDependencyProvider::CLIENT_COMPANY_USER_INVITATION);
    }

    /**
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCustomerClientInterface
     */
    public function getCustomerClient(): CompanyUserInvitationPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CompanyUserInvitationPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToSessionClientInterface
     */
    public function getSessionClient(): CompanyUserInvitationPageToSessionClientInterface
    {
        return $this->getProvidedDependency(CompanyUserInvitationPageDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \SprykerShop\Yves\CompanyUserInvitationPage\Dependency\Client\CompanyUserInvitationPageToCompanyUserClientInterface
     */
    protected function getCompanyUserClient(): CompanyUserInvitationPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(CompanyUserInvitationPageDependencyProvider::CLIENT_COMPANY_USER);
    }
}
