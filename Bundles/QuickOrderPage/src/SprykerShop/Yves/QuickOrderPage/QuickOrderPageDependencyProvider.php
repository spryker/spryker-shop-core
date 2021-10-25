<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToPriceProductStorageClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductQuantityStorageClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuickOrderClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuoteClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToZedRequestClientBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceBridge;
use SprykerShop\Yves\QuickOrderPage\Dependency\Service\QuickOrderPageToUtilCsvServiceInterface;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig getConfig()
 */
class QuickOrderPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CART = 'CLIENT_CART';

    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @var string
     */
    public const CLIENT_QUICK_ORDER = 'CLIENT_QUICK_ORDER';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_QUANTITY_STORAGE = 'CLIENT_PRODUCT_QUANTITY_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRICE_PRODUCT_STORAGE = 'CLIENT_PRICE_PRODUCT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @deprecated Will be removed without replacement.
     * @var string
     */
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @var string
     */
    public const PLUGINS_QUICK_ORDER_PAGE_WIDGETS = 'PLUGINS_QUICK_ORDER_PAGE_WIDGETS';

    /**
     * @var string
     */
    public const PLUGINS_QUICK_ORDER_ITEM_TRANSFER_EXPANDER = 'PLUGINS_QUICK_ORDER_ITEM_TRANSFER_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_QUICK_ORDER_FORM_HANDLER_STRATEGY = 'PLUGINS_QUICK_ORDER_FORM_HANDLER_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_QUICK_ORDER_FORM_COLUMN = 'PLUGINS_QUICK_ORDER_FORM_ADDITIONAL_DATA_COLUMN_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_QUICK_ORDER_ITEM_FILTER = 'PLUGINS_QUICK_ORDER_ITEM_FILTER';

    /**
     * @var string
     */
    public const PLUGINS_QUICK_ORDER_UPLOADED_FILE_PARSER = 'PLUGINS_QUICK_ORDER_UPLOADED_FILE_PARSER';

    /**
     * @var string
     */
    public const PLUGINS_QUICK_ORDER_UPLOADED_FILE_VALIDATOR = 'PLUGINS_QUICK_ORDER_UPLOADED_FILE_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_QUICK_ORDER_FILE_TEMPLATE = 'PLUGINS_QUICK_ORDER_FILE_TEMPLATE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_CSV = 'SERVICE_UTIL_CSV';

    /**
     * @uses \Spryker\Yves\Form\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     * @var string
     */
    public const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addRequestStack($container);
        $container = $this->addApplication($container);
        $container = $this->addCartClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addQuickOrderClient($container);
        $container = $this->addQuickOrderPageWidgetPlugins($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addQuickOrderUtilCsvService($container);
        $container = $this->addCsrfProviderService($container);
        $container = $this->addQuickOrderItemTransferExpanderPlugins($container);
        $container = $this->addQuickOrderFormHandlerStrategyPlugins($container);
        $container = $this->addQuickOrderFormAdditionalDataColumnProviderPlugins($container);
        $container = $this->addQuickOrderItemFilterPlugins($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addPriceProductStorageClient($container);
        $container = $this->addProductQuantityStorageClient($container);
        $container = $this->addQuickOrderFileTemplatePlugins($container);
        $container = $this->addQuickOrderUploadedFileParserPlugins($container);
        $container = $this->addQuickOrderUploadedFileValidatorPlugins($container);

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container->set(static::PLUGIN_APPLICATION, function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderUtilCsvService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_CSV, function (Container $container): QuickOrderPageToUtilCsvServiceInterface {
            return new QuickOrderPageToUtilCsvServiceBridge(
                $container->getLocator()->utilCsv()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCsrfProviderService(Container $container): Container
    {
        $container->set(static::SERVICE_FORM_CSRF_PROVIDER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_FORM_CSRF_PROVIDER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART, function (Container $container) {
            return new QuickOrderPageToCartClientBridge($container->getLocator()->cart()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new QuickOrderPageToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new QuickOrderPageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPriceProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE_PRODUCT_STORAGE, function (Container $container) {
            return new QuickOrderPageToPriceProductStorageClientBridge($container->getLocator()->priceProductStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductQuantityStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_QUANTITY_STORAGE, function (Container $container) {
            return new QuickOrderPageToProductQuantityStorageClientBridge($container->getLocator()->productQuantityStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUICK_ORDER, function (Container $container) {
            return new QuickOrderPageToQuickOrderClientBridge(
                $container->getLocator()->quickOrder()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderPageWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUICK_ORDER_PAGE_WIDGETS, function () {
            return $this->getQuickOrderPageWidgetPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderItemTransferExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUICK_ORDER_ITEM_TRANSFER_EXPANDER, function () {
            return $this->getQuickOrderItemTransferExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addZedRequestClient($container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return new QuickOrderPageToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderFormHandlerStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUICK_ORDER_FORM_HANDLER_STRATEGY, function () {
            return $this->getQuickOrderFormHandlerStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderItemFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUICK_ORDER_ITEM_FILTER, function () {
            return $this->getQuickOrderItemFilterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderFormAdditionalDataColumnProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUICK_ORDER_FORM_COLUMN, function () {
            return $this->getQuickOrderFormColumnPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderUploadedFileParserPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUICK_ORDER_UPLOADED_FILE_PARSER, function (): array {
            return $this->getQuickOrderUploadedFileParserPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderUploadedFileValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUICK_ORDER_UPLOADED_FILE_VALIDATOR, function (): array {
            return $this->getQuickOrderUploadedFileValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuickOrderFileTemplatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUICK_ORDER_FILE_TEMPLATE, function (): array {
            return $this->getQuickOrderFileTemplatePlugins();
        });

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement
     * Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface.
     *
     * @return array<string>
     */
    protected function getQuickOrderPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemExpanderPluginInterface>
     */
    protected function getQuickOrderItemTransferExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemFilterPluginInterface>
     */
    protected function getQuickOrderItemFilterPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormHandlerStrategyPluginInterface>
     */
    protected function getQuickOrderFormHandlerStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFormColumnPluginInterface>
     */
    protected function getQuickOrderFormColumnPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderUploadedFileParserStrategyPluginInterface>
     */
    protected function getQuickOrderUploadedFileParserPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileTemplateStrategyPluginInterface>
     */
    protected function getQuickOrderFileTemplatePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderUploadedFileValidatorStrategyPluginInterface>
     */
    protected function getQuickOrderUploadedFileValidatorPlugins(): array
    {
        return [];
    }
}
