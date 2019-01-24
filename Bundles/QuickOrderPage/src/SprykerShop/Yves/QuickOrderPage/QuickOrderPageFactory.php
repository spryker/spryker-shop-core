<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Application;
use SprykerShop\Yves\QuickOrderPage\AdditionalColumnsProvider\AdditionalColumnsProvider;
use SprykerShop\Yves\QuickOrderPage\AdditionalColumnsProvider\AdditionalColumnsProviderInterface;
use SprykerShop\Yves\QuickOrderPage\Csv\FileParser;
use SprykerShop\Yves\QuickOrderPage\Csv\FileParserInterface;
use SprykerShop\Yves\QuickOrderPage\Csv\FileValidator;
use SprykerShop\Yves\QuickOrderPage\Csv\FileValidatorInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToPriceProductStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductQuantityStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuickOrderClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface;
use SprykerShop\Yves\QuickOrderPage\FileOutputter\FileOutputter;
use SprykerShop\Yves\QuickOrderPage\FileOutputter\FileOutputterInterface;
use SprykerShop\Yves\QuickOrderPage\FileTemplateExtensionsReader\FileTemplateExtensionsReader;
use SprykerShop\Yves\QuickOrderPage\FileTemplateExtensionsReader\FileTemplateExtensionsReaderInterface;
use SprykerShop\Yves\QuickOrderPage\Form\Constraint\ItemsFieldConstraint;
use SprykerShop\Yves\QuickOrderPage\Form\Constraint\QuantityFieldConstraint;
use SprykerShop\Yves\QuickOrderPage\Form\Constraint\TextOrderFormatConstraint;
use SprykerShop\Yves\QuickOrderPage\Form\Constraint\UploadOrderFormatConstraint;
use SprykerShop\Yves\QuickOrderPage\Form\DataProvider\QuickOrderFormDataProvider;
use SprykerShop\Yves\QuickOrderPage\Form\DataProvider\QuickOrderFormDataProviderInterface;
use SprykerShop\Yves\QuickOrderPage\Form\FormFactory;
use SprykerShop\Yves\QuickOrderPage\Form\Handler\QuickOrderFormHandler;
use SprykerShop\Yves\QuickOrderPage\Form\Handler\QuickOrderFormHandlerInterface;
use SprykerShop\Yves\QuickOrderPage\PluginExecutor\QuickOrderItemPluginExecutor;
use SprykerShop\Yves\QuickOrderPage\PriceResolver\PriceResolver;
use SprykerShop\Yves\QuickOrderPage\PriceResolver\PriceResolverInterface;
use SprykerShop\Yves\QuickOrderPage\ProductResolver\ProductResolver;
use SprykerShop\Yves\QuickOrderPage\ProductResolver\ProductResolverInterface;
use SprykerShop\Yves\QuickOrderPage\TextOrder\TextOrderParser;
use SprykerShop\Yves\QuickOrderPage\TextOrder\TextOrderParserInterface;
use SprykerShop\Yves\QuickOrderPage\UploadOrder\UploadedOrderParser;
use SprykerShop\Yves\QuickOrderPage\UploadOrder\UploadedOrderParserInterface;
use SprykerShop\Yves\QuickOrderPage\ViewDataTransformer\ViewDataTransformer;
use SprykerShop\Yves\QuickOrderPage\ViewDataTransformer\ViewDataTransformerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig getConfig()
 */
class QuickOrderPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\FormFactory
     */
    public function createQuickOrderFormFactory(): FormFactory
    {
        return new FormFactory();
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\Handler\QuickOrderFormHandlerInterface
     */
    public function createFormOperationHandler(): QuickOrderFormHandlerInterface
    {
        return new QuickOrderFormHandler(
            $this->getCartClient(),
            $this->getQuoteClient(),
            $this->getZedRequestClient(),
            $this->createProductResolver(),
            $this->getRequest(),
            $this->getQuickOrderItemTransferExpanderPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\FileOutputter\FileOutputterInterface
     */
    public function createFileOutputter(): FileOutputterInterface
    {
        return new FileOutputter(
            $this->getQuickOrderFileTemplatePlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\DataProvider\QuickOrderFormDataProviderInterface
     */
    public function createQuickOrderFormDataProvider(): QuickOrderFormDataProviderInterface
    {
        return new QuickOrderFormDataProvider($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\TextOrder\TextOrderParserInterface
     */
    public function createTextOrderParser(): TextOrderParserInterface
    {
        return new TextOrderParser($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\UploadOrder\UploadedOrderParserInterface
     */
    public function createUploadOrderParser(): UploadedOrderParserInterface
    {
        return new UploadedOrderParser(
            $this->getQuickOrderFileProcessorPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Csv\FileParserInterface
     */
    public function createCsvFileParser(): FileParserInterface
    {
        return new FileParser(
            $this->getUtilCsvService()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface
     */
    public function getCartClient(): QuickOrderPageToCartClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientInterface
     */
    public function getZedRequestClient(): QuickOrderPageToZedRequestClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuickOrderClientInterface
     */
    public function getQuickOrderClient(): QuickOrderPageToQuickOrderClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_QUICK_ORDER);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientInterface
     */
    public function getQuoteClient(): QuickOrderPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest(): Request
    {
        return $this->getApplication()['request'];
    }

    /**
     * Returns a list of widget plugin class names that implement
     * Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return string[]
     */
    public function getQuickOrderPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGINS_QUICK_ORDER_PAGE_WIDGETS);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface[]
     */
    public function getQuickOrderItemTransferExpanderPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGINS_QUICK_ORDER_ITEM_TRANSFER_EXPANDER);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientInterface
     */
    public function getProductStorageClient(): QuickOrderPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): QuickOrderPageToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormHandlerStrategyPluginInterface[]
     */
    public function getQuickOrderFormHandlerStrategyPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGINS_QUICK_ORDER_FORM_HANDLER_STRATEGY);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface[]
     */
    public function getQuickOrderFormColumnPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGINS_QUICK_ORDER_FORM_COLUMN);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemFilterPluginInterface[]
     */
    public function getQuickOrderItemFilterPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGINS_QUICK_ORDER_ITEM_FILTER);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileProcessorStrategyPluginInterface[]
     */
    public function getQuickOrderFileProcessorPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGINS_QUICK_ORDER_FILE_PROCESSOR);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplateStrategyPluginInterface[]
     */
    public function getQuickOrderFileTemplatePlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGINS_QUICK_ORDER_FILE_TEMPLATE);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductQuantityStorageClientInterface
     */
    public function getProductQuantityStorageClient(): QuickOrderPageToProductQuantityStorageClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_PRODUCT_QUANTITY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface
     */
    public function getUtilCsvService(): QuickOrderPageToUtilCsvServiceInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::SERVICE_UTIL_CSV);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\Constraint\QuantityFieldConstraint
     */
    public function createQtyFieldConstraint()
    {
        return new QuantityFieldConstraint();
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\Constraint\ItemsFieldConstraint
     */
    public function createItemsFieldConstraint(): ItemsFieldConstraint
    {
        return new ItemsFieldConstraint();
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\Constraint\TextOrderFormatConstraint
     */
    public function createTextOrderCorrectConstraint(): TextOrderFormatConstraint
    {
        return new TextOrderFormatConstraint(
            [
                TextOrderFormatConstraint::OPTION_BUNDLE_CONFIG => $this->getConfig(),
            ]
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\Constraint\UploadOrderFormatConstraint
     */
    public function createUploadOrderCorrectConstraint(): UploadOrderFormatConstraint
    {
        return new UploadOrderFormatConstraint(
            [
                UploadOrderFormatConstraint::OPTION_BUNDLE_CONFIG => $this->getConfig(),
                UploadOrderFormatConstraint::OPTION_FILE_PROCESSOR_PLUGINS => $this->getQuickOrderFileProcessorPlugins(),
            ]
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    public function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank();
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\PluginExecutor\QuickOrderItemPluginExecutor
     */
    public function createQuickOrderItemPluginExecutor(): QuickOrderItemPluginExecutor
    {
        return new QuickOrderItemPluginExecutor(
            $this->getQuickOrderItemFilterPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\ProductResolver\ProductResolverInterface
     */
    public function createProductResolver(): ProductResolverInterface
    {
        return new ProductResolver(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\PriceResolver\PriceResolverInterface
     */
    public function createPriceResolver(): PriceResolverInterface
    {
        return new PriceResolver(
            $this->createProductResolver(),
            $this->getPriceProductStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\ViewDataTransformer\ViewDataTransformerInterface
     */
    public function createViewDataTransformer(): ViewDataTransformerInterface
    {
        return new ViewDataTransformer();
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Csv\FileValidatorInterface
     */
    public function createCsvFileValidator(): FileValidatorInterface
    {
        return new FileValidator($this->getUtilCsvService());
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\FileTemplateExtensionsReader\FileTemplateExtensionsReaderInterface
     */
    public function createFileTemplateExtensionsReader(): FileTemplateExtensionsReaderInterface
    {
        return new FileTemplateExtensionsReader($this->getQuickOrderFileTemplatePlugins());
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\AdditionalColumnsProvider\AdditionalColumnsProviderInterface
     */
    public function createAdditionalColumnsProvider(): AdditionalColumnsProviderInterface
    {
        return new AdditionalColumnsProvider($this->getQuickOrderFormColumnPlugins());
    }
}
