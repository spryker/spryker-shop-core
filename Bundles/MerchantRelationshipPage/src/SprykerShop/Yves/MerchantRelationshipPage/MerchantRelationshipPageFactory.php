<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyUserClientInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantRelationshipClientInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantSearchClientInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Form\DataProvider\MerchantRelationshipSearchFormDataProvider;
use SprykerShop\Yves\MerchantRelationshipPage\Form\Handler\MerchantRelationshipSearchHandler;
use SprykerShop\Yves\MerchantRelationshipPage\Form\Handler\MerchantRelationshipSearchHandlerInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Form\MerchantRelationshipSearchForm;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyBusinessUnitReader;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyBusinessUnitReaderInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyUserReader;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyUserReaderInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\MerchantSearchReader;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\MerchantSearchReaderInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\MerchantStorageReader;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\MerchantStorageReaderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageConfig getConfig()
 */
class MerchantRelationshipPageFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantRelationshipSearchForm(): FormInterface
    {
        return $this->getFormFactory()->create(
            MerchantRelationshipSearchForm::class,
            null,
            $this->createMerchantRelationshipSearchFormDataProvider()->getOptions(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Form\DataProvider\MerchantRelationshipSearchFormDataProvider
     */
    public function createMerchantRelationshipSearchFormDataProvider(): MerchantRelationshipSearchFormDataProvider
    {
        return new MerchantRelationshipSearchFormDataProvider(
            $this->createMerchantSearchReader(),
            $this->createCompanyBusinessUnitReader(),
            $this->createCompanyUserReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Form\Handler\MerchantRelationshipSearchHandlerInterface
     */
    public function createMerchantRelationshipSearchHandler(): MerchantRelationshipSearchHandlerInterface
    {
        return new MerchantRelationshipSearchHandler(
            $this->getMerchantRelationshipClient(),
            $this->createCompanyUserReader(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Reader\MerchantStorageReaderInterface
     */
    public function createMerchantStorageReader(): MerchantStorageReaderInterface
    {
        return new MerchantStorageReader(
            $this->getMerchantStorageClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyBusinessUnitReaderInterface
     */
    public function createCompanyBusinessUnitReader(): CompanyBusinessUnitReaderInterface
    {
        return new CompanyBusinessUnitReader(
            $this->getCompanyBusinessUnitClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Reader\MerchantSearchReaderInterface
     */
    public function createMerchantSearchReader(): MerchantSearchReaderInterface
    {
        return new MerchantSearchReader(
            $this->getMerchantSearchClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader(
            $this->getCompanyUserClient(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantRelationshipClientInterface
     */
    public function getMerchantRelationshipClient(): MerchantRelationshipPageToMerchantRelationshipClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipPageDependencyProvider::CLIENT_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): MerchantRelationshipPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyBusinessUnitClientInterface
     */
    public function getCompanyBusinessUnitClient(): MerchantRelationshipPageToCompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipPageDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantRelationshipPageToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipPageDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantSearchClientInterface
     */
    public function getMerchantSearchClient(): MerchantRelationshipPageToMerchantSearchClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipPageDependencyProvider::CLIENT_MERCHANT_SEARCH);
    }
}
