<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Application;
use SprykerShop\Yves\QuickOrderPage\ColumnProvider\QuickOrderFormAdditionalColumnProvider;
use SprykerShop\Yves\QuickOrderPage\ColumnProvider\QuickOrderFormAdditionalColumnProviderInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToPriceProductStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductQuantityStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuickOrderClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface;
use SprykerShop\Yves\QuickOrderPage\File\ExtensionReader\FileTemplateExtensionReader;
use SprykerShop\Yves\QuickOrderPage\File\ExtensionReader\FileTemplateExtensionReaderInterface;
use SprykerShop\Yves\QuickOrderPage\File\Parser\FileParserInterface;
use SprykerShop\Yves\QuickOrderPage\File\Parser\FileValidatorInterface;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\CsvType\UploadedFileCsvTypeParser;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\CsvType\UploadedFileCsvTypeSanitizer;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\CsvType\UploadedFileCsvTypeValidator;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileCsvTypeSanitizerInterface;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileParser;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileTypeParserInterface;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileTypeValidatorInterface;
use SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileValidator;
use SprykerShop\Yves\QuickOrderPage\File\Renderer\FileDownloadRenderer;
use SprykerShop\Yves\QuickOrderPage\File\Renderer\FileRendererInterface;
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
     * @return \SprykerShop\Yves\QuickOrderPage\File\Renderer\FileRendererInterface
     */
    public function createFileDownloadRenderer(): FileRendererInterface
    {
        return new FileDownloadRenderer(
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
     * @return \SprykerShop\Yves\QuickOrderPage\File\Parser\FileParserInterface
     */
    public function createUploadedFileParser(): FileParserInterface
    {
        return new UploadedFileParser(
            $this->getQuickOrderUploadedFileParserPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\File\Parser\FileValidatorInterface
     */
    public function createUploadedFileValidator(): FileValidatorInterface
    {
        return new UploadedFileValidator(
            $this->getQuickOrderUploadedFileValidatorPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileTypeValidatorInterface
     */
    public function createUploadedFileCsvTypeValidator(): UploadedFileTypeValidatorInterface
    {
        return new UploadedFileCsvTypeValidator($this->getUtilCsvService());
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileTypeParserInterface
     */
    public function createUploadedFileCsvTypeParser(): UploadedFileTypeParserInterface
    {
        return new UploadedFileCsvTypeParser(
            $this->getUtilCsvService(),
            $this->createUploadedFileCsvTypeSanitizer()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile\UploadedFileCsvTypeSanitizerInterface
     */
    public function createUploadedFileCsvTypeSanitizer(): UploadedFileCsvTypeSanitizerInterface
    {
        return new UploadedFileCsvTypeSanitizer();
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
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderUploadedFileParserStrategyPluginInterface[]
     */
    public function getQuickOrderUploadedFileParserPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGINS_QUICK_ORDER_UPLOADED_FILE_PARSER);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderUploadedFileValidatorStrategyPluginInterface[]
     */
    public function getQuickOrderUploadedFileValidatorPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGINS_QUICK_ORDER_UPLOADED_FILE_VALIDATOR);
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
                UploadOrderFormatConstraint::OPTION_UPLOADED_FILE_VALIDATOR => $this->createUploadedFileValidator(),
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
     * @return \SprykerShop\Yves\QuickOrderPage\File\ExtensionReader\FileTemplateExtensionReaderInterface
     */
    public function createFileTemplateExtensionsReader(): FileTemplateExtensionReaderInterface
    {
        return new FileTemplateExtensionReader($this->getQuickOrderFileTemplatePlugins());
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\ColumnProvider\QuickOrderFormAdditionalColumnProviderInterface
     */
    public function createAdditionalColumnsProvider(): QuickOrderFormAdditionalColumnProviderInterface
    {
        return new QuickOrderFormAdditionalColumnProvider($this->getQuickOrderFormColumnPlugins());
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    public function getModuleConfig(): QuickOrderPageConfig
    {
        return $this->getConfig();
    }
}
