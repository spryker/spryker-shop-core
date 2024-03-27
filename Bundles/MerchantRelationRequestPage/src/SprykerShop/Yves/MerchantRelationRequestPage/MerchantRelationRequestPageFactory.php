<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyUserClientInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCustomerClientInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantRelationRequestClientInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantSearchClientInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\DataProvider\MerchantRelationRequestFormDataProvider;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\DataProvider\MerchantRelationRequestSearchFormDataProvider;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\Handler\MerchantRelationRequestHandler;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\Handler\MerchantRelationRequestHandlerInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\Handler\MerchantRelationRequestSearchHandler;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\Handler\MerchantRelationRequestSearchHandlerInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\MerchantRelationRequestForm;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\MerchantRelationRequestSearchForm;
use SprykerShop\Yves\MerchantRelationRequestPage\Hydrator\MerchantRelationRequestFormHydrator;
use SprykerShop\Yves\MerchantRelationRequestPage\Hydrator\MerchantRelationRequestFormHydratorInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyBusinessUnitReader;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyBusinessUnitReaderInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReader;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantSearchReader;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantSearchReaderInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantStorageReader;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantStorageReaderInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Transformer\CompanyBusinessUnitTransformer;
use SprykerShop\Yves\MerchantRelationRequestPage\Transformer\CompanyBusinessUnitTransformerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig getConfig()
 */
class MerchantRelationRequestPageFactory extends AbstractFactory
{
    /**
     * @param string $merchantReference
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantRelationRequestForm(string $merchantReference): FormInterface
    {
        $dataProvider = $this->createMerchantRelationRequestFormDataProvider();
        $merchantRelationRequestTransfer = $dataProvider->getData($merchantReference);

        return $this->getFormFactory()->create(
            MerchantRelationRequestForm::class,
            $merchantRelationRequestTransfer,
            $dataProvider->getOptions($merchantRelationRequestTransfer),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantRelationRequestSearchForm(): FormInterface
    {
        return $this->getFormFactory()->create(
            MerchantRelationRequestSearchForm::class,
            null,
            $this->createMerchantRelationRequestSearchFormDataProvider()->getOptions(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader(
            $this->getCompanyUserClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Form\Handler\MerchantRelationRequestSearchHandlerInterface
     */
    public function createMerchantRelationRequestSearchHandler(): MerchantRelationRequestSearchHandlerInterface
    {
        return new MerchantRelationRequestSearchHandler(
            $this->getMerchantRelationRequestClient(),
            $this->createCompanyUserReader(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Transformer\CompanyBusinessUnitTransformerInterface
     */
    public function createCompanyBusinessUnitTransformer(): CompanyBusinessUnitTransformerInterface
    {
        return new CompanyBusinessUnitTransformer();
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Form\DataProvider\MerchantRelationRequestFormDataProvider
     */
    public function createMerchantRelationRequestFormDataProvider(): MerchantRelationRequestFormDataProvider
    {
        return new MerchantRelationRequestFormDataProvider(
            $this->getCustomerClient(),
            $this->createMerchantSearchReader(),
            $this->createCompanyBusinessUnitReader(),
            $this->createCompanyUserReader(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Form\DataProvider\MerchantRelationRequestSearchFormDataProvider
     */
    public function createMerchantRelationRequestSearchFormDataProvider(): MerchantRelationRequestSearchFormDataProvider
    {
        return new MerchantRelationRequestSearchFormDataProvider(
            $this->createMerchantSearchReader(),
            $this->createCompanyBusinessUnitReader(),
            $this->createCompanyUserReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantSearchReaderInterface
     */
    public function createMerchantSearchReader(): MerchantSearchReaderInterface
    {
        return new MerchantSearchReader(
            $this->getMerchantSearchClient(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Form\Handler\MerchantRelationRequestHandlerInterface
     */
    public function createMerchantRelationRequestHandler(): MerchantRelationRequestHandlerInterface
    {
        return new MerchantRelationRequestHandler(
            $this->getMerchantRelationRequestClient(),
            $this->createCompanyUserReader(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyBusinessUnitReaderInterface
     */
    public function createCompanyBusinessUnitReader(): CompanyBusinessUnitReaderInterface
    {
        return new CompanyBusinessUnitReader(
            $this->getCompanyBusinessUnitClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantStorageReaderInterface
     */
    public function createMerchantStorageReader(): MerchantStorageReaderInterface
    {
        return new MerchantStorageReader(
            $this->getMerchantStorageClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Hydrator\MerchantRelationRequestFormHydratorInterface
     */
    public function createMerchantRelationRequestFormHydrator(): MerchantRelationRequestFormHydratorInterface
    {
        return new MerchantRelationRequestFormHydrator();
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): MerchantRelationRequestPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyBusinessUnitClientInterface
     */
    public function getCompanyBusinessUnitClient(): MerchantRelationRequestPageToCompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestPageDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCustomerClientInterface
     */
    public function getCustomerClient(): MerchantRelationRequestPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantSearchClientInterface
     */
    public function getMerchantSearchClient(): MerchantRelationRequestPageToMerchantSearchClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestPageDependencyProvider::CLIENT_MERCHANT_SEARCH);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantRelationRequestPageToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestPageDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantRelationRequestClientInterface
     */
    public function getMerchantRelationRequestClient(): MerchantRelationRequestPageToMerchantRelationRequestClientInterface
    {
        return $this->getProvidedDependency(MerchantRelationRequestPageDependencyProvider::CLIENT_MERCHANT_RELATION_REQUEST);
    }
}
