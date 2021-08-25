<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Checker\ProductDetailPageApplicabilityChecker;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Checker\ProductDetailPageApplicabilityCheckerInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToGlossaryStorageClientBridge;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductStorageClientInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Expander\ProductDetailPageProductConfiguratorRequestDataFormExpander;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Expander\ProductDetailPageProductConfiguratorRequestDataFormExpanderInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\DataProvider\ProductConfiguratorRequestDataFormDataProvider;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\ProductConfiguratorRequestDataForm;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorRequestMapper;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorRequestMapperInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorResponseDataMapper;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorResponseDataMapperInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Processor\ProductConfiguratorResponseProcessor;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Processor\ProductConfiguratorResponseProcessorInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Processor\ProductDetailPageProductConfiguratorResponseProcessor;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Processor\ProductDetailPageProductConfiguratorResponseProcessorInerface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductConfiguratorRedirectResolver;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductConfiguratorRedirectResolverInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductDetailPageBackUrlResolver;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductDetailPageBackUrlResolverInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductDetailPageProductConfiguratorRedirectResolver;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductDetailPageProductConfiguratorRedirectResolverInterface;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Validator\ProductConfiguratorResponseValidator;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Validator\ProductConfiguratorResponseValidatorInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig getConfig()
 */
class ProductConfiguratorGatewayPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorResponseDataMapperInterface
     */
    public function createProductConfiguratorResponseDataMapper(): ProductConfiguratorResponseDataMapperInterface
    {
        return new ProductConfiguratorResponseDataMapper();
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductConfiguratorRedirectResolverInterface
     */
    public function createProductConfiguratorRedirectResolver(): ProductConfiguratorRedirectResolverInterface
    {
        return new ProductConfiguratorRedirectResolver(
            $this->getProductConfiguratorRequestPlugins()
        );
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductConfiguratorRequestDataForm(array $options = []): FormInterface
    {
        return $this->getFormFactory()->createNamed(
            $this->getConfig()->getProductConfiguratorGatewayRequestFormName(),
            ProductConfiguratorRequestDataForm::class,
            null,
            $options
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\DataProvider\ProductConfiguratorRequestDataFormDataProvider
     */
    public function createProductConfiguratorRequestDataFormDataProvider(): ProductConfiguratorRequestDataFormDataProvider
    {
        return new ProductConfiguratorRequestDataFormDataProvider(
            $this->getConfig()
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
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductDetailPageProductConfiguratorRedirectResolverInterface
     */
    public function createProductDetailPageProductConfiguratorRedirectResolver(): ProductDetailPageProductConfiguratorRedirectResolverInterface
    {
        return new ProductDetailPageProductConfiguratorRedirectResolver(
            $this->getProductConfigurationClient(),
            $this->getProductConfigurationStorageClient(),
            $this->createProductConfiguratorRequestMapper()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Checker\ProductDetailPageApplicabilityCheckerInterface
     */
    public function createProductDetailPageApplicabilityChecker(): ProductDetailPageApplicabilityCheckerInterface
    {
        return new ProductDetailPageApplicabilityChecker(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Mapper\ProductConfiguratorRequestMapperInterface
     */
    public function createProductConfiguratorRequestMapper(): ProductConfiguratorRequestMapperInterface
    {
        return new ProductConfiguratorRequestMapper();
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Processor\ProductConfiguratorResponseProcessorInterface
     */
    public function createProductConfiguratorResponseProcessor(): ProductConfiguratorResponseProcessorInterface
    {
        return new ProductConfiguratorResponseProcessor(
            $this->getProductConfiguratorResponsePlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Processor\ProductDetailPageProductConfiguratorResponseProcessorInerface
     */
    public function createProductDetailPageProductConfiguratorResponseProcessor(): ProductDetailPageProductConfiguratorResponseProcessorInerface
    {
        return new ProductDetailPageProductConfiguratorResponseProcessor(
            $this->getProductConfigurationClient(),
            $this->getProductConfigurationStorageClient(),
            $this->createProductConfiguratorResponseValidator(),
            $this->createProductDetailPageBackUrlResolver()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Validator\ProductConfiguratorResponseValidatorInterface
     */
    public function createProductConfiguratorResponseValidator(): ProductConfiguratorResponseValidatorInterface
    {
        return new ProductConfiguratorResponseValidator(
            $this->getProductConfigurationClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Resolver\ProductDetailPageBackUrlResolverInterface
     */
    public function createProductDetailPageBackUrlResolver(): ProductDetailPageBackUrlResolverInterface
    {
        return new ProductDetailPageBackUrlResolver(
            $this->getProductStorageClient(),
            $this->getRouter()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface
     */
    public function getProductConfigurationStorageClient(): ProductConfiguratorGatewayPageToProductConfigurationStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfiguratorGatewayPageDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductConfigurationClientInterface
     */
    public function getProductConfigurationClient(): ProductConfiguratorGatewayPageToProductConfigurationClientInterface
    {
        return $this->getProvidedDependency(ProductConfiguratorGatewayPageDependencyProvider::CLIENT_PRODUCT_CONFIGURATION);
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductConfiguratorGatewayPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfiguratorGatewayPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    public function getRouter(): ChainRouterInterface
    {
        return $this->getProvidedDependency(ProductConfiguratorGatewayPageDependencyProvider::SERVICE_ROUTER);
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Dependency\Client\ProductConfiguratorGatewayPageToGlossaryStorageClientBridge
     */
    public function getGlossaryStorageClient(): ProductConfiguratorGatewayPageToGlossaryStorageClientBridge
    {
        return $this->getProvidedDependency(ProductConfiguratorGatewayPageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestStrategyPluginInterface[]
     */
    public function getProductConfiguratorRequestPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfiguratorGatewayPageDependencyProvider::PLUGINS_PRODUCT_CONFIGURATOR_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorResponseStrategyPluginInterface[]
     */
    public function getProductConfiguratorResponsePlugins(): array
    {
        return $this->getProvidedDependency(ProductConfiguratorGatewayPageDependencyProvider::PLUGINS_PRODUCT_CONFIGURATOR_RESPONSE);
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPageExtension\Dependency\Plugin\ProductConfiguratorRequestDataFormExpanderStrategyPluginInterface[]
     */
    public function getProductConfiguratorRequestDataFormExpanderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfiguratorGatewayPageDependencyProvider::PLUGINS_PRODUCT_CONFIGURATOR_REQUEST_DATA_FORM_EXPANDER_STRATEGY);
    }

    /**
     * @return \SprykerShop\Yves\ProductConfiguratorGatewayPage\Expander\ProductDetailPageProductConfiguratorRequestDataFormExpanderInterface
     */
    public function createProductDetailPageProductConfiguratorRequestDataFormExpander(): ProductDetailPageProductConfiguratorRequestDataFormExpanderInterface
    {
        return new ProductDetailPageProductConfiguratorRequestDataFormExpander($this->getConfig());
    }
}
