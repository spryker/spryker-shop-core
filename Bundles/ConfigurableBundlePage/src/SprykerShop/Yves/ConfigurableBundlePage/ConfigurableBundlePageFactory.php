<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleCartClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundlePageSearchClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToGlossaryStorageClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToPriceProductStorageClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToProductImageStorageClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Expander\ItemExpander;
use SprykerShop\Yves\ConfigurableBundlePage\Expander\ItemExpanderInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Expander\ProductConcreteImageExpander;
use SprykerShop\Yves\ConfigurableBundlePage\Expander\ProductConcreteImageExpanderInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Expander\ProductConcretePriceExpander;
use SprykerShop\Yves\ConfigurableBundlePage\Expander\ProductConcretePriceExpanderInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Form\ConfiguratorStateForm;
use SprykerShop\Yves\ConfigurableBundlePage\Mapper\ConfiguredBundleRequestMapper;
use SprykerShop\Yves\ConfigurableBundlePage\Mapper\ConfiguredBundleRequestMapperInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Reader\ConfigurableBundleTemplateStorageReader;
use SprykerShop\Yves\ConfigurableBundlePage\Reader\ConfigurableBundleTemplateStorageReaderInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Reader\ProductConcreteReader;
use SprykerShop\Yves\ConfigurableBundlePage\Reader\ProductConcreteReaderInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Sanitizer\ConfiguratorStateSanitizer;
use SprykerShop\Yves\ConfigurableBundlePage\Sanitizer\ConfiguratorStateSanitizerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ConfigurableBundlePageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Reader\ConfigurableBundleTemplateStorageReaderInterface
     */
    public function createConfigurableBundleTemplateStorageReader(): ConfigurableBundleTemplateStorageReaderInterface
    {
        return new ConfigurableBundleTemplateStorageReader($this->getConfigurableBundleStorageClient());
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Mapper\ConfiguredBundleRequestMapperInterface
     */
    public function createConfiguredBundleRequestMapper(): ConfiguredBundleRequestMapperInterface
    {
        return new ConfiguredBundleRequestMapper($this->createItemExpander());
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Reader\ProductConcreteReaderInterface
     */
    public function createProductConcreteReader(): ProductConcreteReaderInterface
    {
        return new ProductConcreteReader(
            $this->getConfigurableBundleStorageClient(),
            $this->createProductConcreteImageExpander(),
            $this->createProductConcretePriceExpander(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Expander\ProductConcreteImageExpanderInterface
     */
    public function createProductConcreteImageExpander(): ProductConcreteImageExpanderInterface
    {
        return new ProductConcreteImageExpander($this->getProductImageStorageClient());
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Expander\ProductConcretePriceExpanderInterface
     */
    public function createProductConcretePriceExpander(): ProductConcretePriceExpanderInterface
    {
        return new ProductConcretePriceExpander($this->getPriceProductStorageClient());
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Expander\ItemExpanderInterface
     */
    public function createItemExpander(): ItemExpanderInterface
    {
        return new ItemExpander($this->createProductConcreteReader());
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Sanitizer\ConfiguratorStateSanitizerInterface
     */
    public function createConfiguratorStateSanitizer(): ConfiguratorStateSanitizerInterface
    {
        return new ConfiguratorStateSanitizer();
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getConfiguratorStateForm(array $data = [], array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ConfiguratorStateForm::class, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundlePageSearchClientInterface
     */
    public function getConfigurableBundlePageSearchClient(): ConfigurableBundlePageToConfigurableBundlePageSearchClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_PAGE_SEARCH);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface
     */
    public function getConfigurableBundleStorageClient(): ConfigurableBundlePageToConfigurableBundleStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleCartClientInterface
     */
    public function getConfigurableBundleCartClient(): ConfigurableBundlePageToConfigurableBundleCartClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_CART);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToProductImageStorageClientInterface
     */
    public function getProductImageStorageClient(): ConfigurableBundlePageToProductImageStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageDependencyProvider::CLIENT_PRODUCT_IMAGE_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): ConfigurableBundlePageToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ConfigurableBundlePageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
