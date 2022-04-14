<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\Checkout\CheckoutDependencyProvider as SprykerCheckoutDependencyProvider;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCartClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToUtilValidateServiceBridge;
use SprykerShop\Yves\CheckoutPage\Plugin\CheckoutBreadcrumbPlugin;
use SprykerShop\Yves\CheckoutPage\Plugin\ShipmentFormDataProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Plugin\ShipmentHandlerPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\CheckoutPage\CheckoutAddressFormDataProviderPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\CustomerStepHandler;
use SprykerShop\Yves\MoneyWidget\Plugin\MoneyPlugin;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class CheckoutPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @var string
     */
    public const CLIENT_CALCULATION = 'CLIENT_CALCULATION';

    /**
     * @var string
     */
    public const CLIENT_CHECKOUT = 'CLIENT_CHECKOUT';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_CART = 'CLIENT_CART';

    /**
     * @var string
     */
    public const CLIENT_SHIPMENT = 'CLIENT_SHIPMENT';

    /**
     * @var string
     */
    public const CLIENT_PAYMENT = 'CLIENT_PAYMENT';

    /**
     * @var string
     */
    public const CLIENT_PRICE = 'CLIENT_PRICE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_BUNDLE = 'CLIENT_PRODUCT_BUNDLE';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_VALIDATE = 'SERVICE_UTIL_VALIDATE';

    /**
     * @var string
     */
    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';

    /**
     * @var string
     */
    public const SERVICE_CUSTOMER = 'SERVICE_CUSTOMER';

    /**
     * @uses \Spryker\Yves\Messenger\Plugin\Application\FlashMessengerApplicationPlugin::SERVICE_FLASH_MESSENGER
     *
     * @var string
     */
    public const SERVICE_FLASH_MESSENGER = 'flash_messenger';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @var string
     */
    public const PLUGIN_CUSTOMER_STEP_HANDLER = 'PLUGIN_CUSTOMER_STEP_HANDLER';

    /**
     * @var string
     */
    public const PLUGIN_SHIPMENT_STEP_HANDLER = 'PLUGIN_SHIPMENT_STEP_HANDLER';

    /**
     * @var string
     */
    public const PLUGIN_SHIPMENT_HANDLER = 'PLUGIN_SHIPMENT_HANDLER';

    /**
     * @var string
     */
    public const PLUGIN_SHIPMENT_FORM_DATA_PROVIDER = 'PLUGIN_SHIPMENT_FORM_DATA_PROVIDER';

    /**
     * @var string
     */
    public const PLUGIN_CHECKOUT_ADDRESS_FORM_DATA_PROVIDER = 'PLUGIN_CHECKOUT_ADDRESS_FORM_DATA_PROVIDER';

    /**
     * @var string
     */
    public const PLUGIN_CHECKOUT_BREADCRUMB = 'PLUGIN_CHECKOUT_BREADCRUMB';

    /**
     * @var string
     */
    public const PLUGIN_MONEY = 'PLUGIN_MONEY';

    /**
     * @var string
     */
    public const PLUGIN_CUSTOMER_PAGE_WIDGETS = 'PLUGIN_CUSTOMER_PAGE_WIDGETS';

    /**
     * @var string
     */
    public const PLUGIN_ADDRESS_PAGE_WIDGETS = 'PLUGIN_ADDRESS_PAGE_WIDGETS';

    /**
     * @var string
     */
    public const PLUGIN_SHIPMENT_PAGE_WIDGETS = 'PLUGIN_SHIPMENT_PAGE_WIDGETS';

    /**
     * @var string
     */
    public const PLUGIN_PAYMENT_PAGE_WIDGETS = 'PLUGIN_PAYMENT_PAGE_WIDGETS';

    /**
     * @var string
     */
    public const PLUGIN_SUMMARY_PAGE_WIDGETS = 'PLUGIN_SUMMARY_PAGE_WIDGETS';

    /**
     * @var string
     */
    public const PLUGIN_SUCCESS_PAGE_WIDGETS = 'PLUGIN_SUCCESS_PAGE_WIDGETS';

    /**
     * @var string
     */
    public const PAYMENT_METHOD_HANDLER = SprykerCheckoutDependencyProvider::PAYMENT_METHOD_HANDLER; // constant value must be BC because of dependency injector

    /**
     * @var string
     */
    public const PAYMENT_SUB_FORMS = SprykerCheckoutDependencyProvider::PAYMENT_SUB_FORMS; // constant value must be BC because of dependency injector

    /**
     * @var string
     */
    public const CUSTOMER_STEP_SUB_FORMS = 'CUSTOMER_STEP_SUB_FORMS';

    /**
     * @var string
     */
    public const ADDRESS_STEP_SUB_FORMS = 'ADDRESS_STEP_SUB_FORMS';

    /**
     * @var string
     */
    public const PLUGIN_SUB_FORM_FILTERS = 'PLUGIN_SUB_FORM_FILTERS';

    /**
     * @var string
     */
    public const PLUGIN_ADDRESS_STEP_EXECUTOR_ADDRESS_TRANSFER_EXPANDERS = 'PLUGIN_ADDRESS_STEP_EXECUTOR_ADDRESS_TRANSFER_EXPANDERS';

    /**
     * @var string
     */
    public const PLUGINS_CHECKOUT_ADDRESS_STEP_ENTER_PRE_CHECK = 'PLUGINS_CHECKOUT_ADDRESS_STEP_ENTER_PRE_CHECK';

    /**
     * @var string
     */
    public const PLUGINS_CHECKOUT_SHIPMENT_STEP_ENTER_PRE_CHECK = 'PLUGINS_CHECKOUT_SHIPMENT_STEP_ENTER_PRE_CHECK';

    /**
     * @var string
     */
    public const PLUGINS_CHECKOUT_PAYMENT_STEP_ENTER_PRE_CHECK = 'PLUGINS_CHECKOUT_PAYMENT_STEP_ENTER_PRE_CHECK';

    /**
     * @var string
     */
    public const PLUGINS_CHECKOUT_STEP_RESOLVER_STRATEGY = 'PLUGINS_CHECKOUT_STEP_RESOLVER_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_CHECKOUT_PAGE_STEP_ENGINE_PRE_RENDER = 'PLUGINS_CHECKOUT_PAGE_STEP_ENGINE_PRE_RENDER';

    /**
     * @var string
     */
    public const PLUGINS_PAYMENT_COLLECTION_EXTENDER = 'PLUGINS_PAYMENT_COLLECTION_EXTENDER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addQuoteClient($container);
        $container = $this->addCalculationClient($container);
        $container = $this->addCheckoutClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addShipmentClient($container);
        $container = $this->addPaymentClient($container);
        $container = $this->addPriceClient($container);
        $container = $this->addProductBundleClient($container);
        $container = $this->addLocaleClient($container);

        $container = $this->addFlashMessenger($container);
        $container = $this->addRouter($container);
        $container = $this->addApplication($container);
        $container = $this->addUtilValidateService($container);

        $container = $this->addSubFormPluginCollection($container);
        $container = $this->addPaymentMethodHandlerPluginCollection($container);
        $container = $this->addSubFormFilterPlugins($container);
        $container = $this->addCustomerStepHandlerPlugin($container);
        $container = $this->addShipmentHandlerPluginCollection($container);
        $container = $this->addShipmentFormDataProviderPlugin($container);
        $container = $this->addMoneyPlugin($container);
        $container = $this->addCheckoutBreadcrumbPlugin($container);
        $container = $this->addCustomerPageWidgetPlugins($container);
        $container = $this->addAddressPageWidgetPlugins($container);
        $container = $this->addShipmentPageWidgetPlugins($container);
        $container = $this->addPaymentPageWidgetPlugins($container);
        $container = $this->addSummaryPageWidgetPlugins($container);
        $container = $this->addSuccessPageWidgetPlugins($container);
        $container = $this->addCheckoutAddressStepEnterPreCheckPlugins($container);
        $container = $this->addCheckoutShipmentStepEnterPreCheckPlugins($container);
        $container = $this->addCheckoutPaymentStepEnterPreCheckPlugins($container);
        $container = $this->addCheckoutAddressFormDataProviderPlugin($container);

        $container = $this->addCustomerStepSubForms($container);
        $container = $this->addAddressStepSubForms($container);
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addShipmentService($container);
        $container = $this->addCustomerService($container);
        $container = $this->addAddressStepExecutorAddressTransferExpanderPlugins($container);
        $container = $this->addCheckoutStepResolverStrategyPlugins($container);
        $container = $this->addCheckoutPageStepEnginePreRenderPlugins($container);

        $container = $this->addPaymentCollectionExtenderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilValidateService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_VALIDATE, function (Container $container) {
            return new CheckoutPageToUtilValidateServiceBridge($container->getLocator()->utilValidate()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutBreadcrumbPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CHECKOUT_BREADCRUMB, function () {
            return new CheckoutBreadcrumbPlugin();
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
        $container->set(static::CLIENT_QUOTE, function () use ($container) {
            return new CheckoutPageToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCalculationClient(Container $container): Container
    {
        $container->set(static::CLIENT_CALCULATION, function (Container $container) {
            return new CheckoutPageToCalculationClientBridge($container->getLocator()->calculation()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutClient(Container $container): Container
    {
        $container->set(static::CLIENT_CHECKOUT, function (Container $container) {
            return new CheckoutPageToCheckoutClientBridge($container->getLocator()->checkout()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new CheckoutPageToCustomerClientBridge($container->getLocator()->customer()->client());
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
            return new CheckoutPageToCartClientBridge($container->getLocator()->cart()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShipmentClient(Container $container): Container
    {
        $container->set(static::CLIENT_SHIPMENT, function (Container $container) {
            return new CheckoutPageToShipmentClientBridge($container->getLocator()->shipment()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPaymentClient(Container $container): Container
    {
        $container->set(static::CLIENT_PAYMENT, function (Container $container) {
            return new CheckoutPageToPaymentClientBridge($container->getLocator()->payment()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPriceClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRICE, function (Container $container) {
            return new CheckoutPageToPriceClientBridge($container->getLocator()->price()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductBundleClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_BUNDLE, function (Container $container) {
            return new CheckoutPageToProductBundleClientBridge($container->getLocator()->productBundle()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_MONEY, function () {
            return new MoneyPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerStepSubForms(Container $container): Container
    {
        $container->set(static::CUSTOMER_STEP_SUB_FORMS, function () {
            return $this->getCustomerStepSubForms();
        });

        return $container;
    }

    /**
     * @return array<\Symfony\Component\Form\FormTypeInterface|string>
     */
    protected function getCustomerStepSubForms(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAddressStepSubForms(Container $container): Container
    {
        $container->set(static::ADDRESS_STEP_SUB_FORMS, function () {
            return $this->getAddressStepSubForms();
        });

        return $container;
    }

    /**
     * @return array<string>
     */
    protected function getAddressStepSubForms(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSubFormPluginCollection(Container $container): Container
    {
        $container->set(static::PAYMENT_SUB_FORMS, function () {
            return new SubFormPluginCollection();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPaymentMethodHandlerPluginCollection(Container $container): Container
    {
        $container->set(static::PAYMENT_METHOD_HANDLER, function () {
            return new StepHandlerPluginCollection();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerStepHandlerPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CUSTOMER_STEP_HANDLER, function () {
            return $this->getCustomerStepHandler();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShipmentHandlerPluginCollection(Container $container): Container
    {
        $container->set(static::PLUGIN_SHIPMENT_HANDLER, function () {
            $shipmentHandlerPlugins = new StepHandlerPluginCollection();
            $shipmentHandlerPlugins->add(new ShipmentHandlerPlugin(), self::PLUGIN_SHIPMENT_STEP_HANDLER);

            return $shipmentHandlerPlugins;
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShipmentFormDataProviderPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SHIPMENT_FORM_DATA_PROVIDER, function () {
            return new ShipmentFormDataProviderPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFlashMessenger(Container $container): Container
    {
        $container->set(static::SERVICE_FLASH_MESSENGER, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_FLASH_MESSENGER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRouter(Container $container): Container
    {
        $container->set(static::SERVICE_ROUTER, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_ROUTER);
        });

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
    protected function addSummaryPageWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_SUMMARY_PAGE_WIDGETS, function () {
            return $this->getSummaryPageWidgetPlugins();
        });

        return $container;
    }

    /**
     * @return array<string>
     */
    protected function getSummaryPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerPageWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CUSTOMER_PAGE_WIDGETS, function () {
            return $this->getCustomerPageWidgetPlugins();
        });

        return $container;
    }

    /**
     * @return array<string>
     */
    protected function getCustomerPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAddressPageWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_ADDRESS_PAGE_WIDGETS, function () {
            return $this->getAddressPageWidgetPlugins();
        });

        return $container;
    }

    /**
     * @return array<string>
     */
    protected function getAddressPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShipmentPageWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_SHIPMENT_PAGE_WIDGETS, function () {
            return $this->getShipmentPageWidgetPlugins();
        });

        return $container;
    }

    /**
     * @return array<string>
     */
    protected function getShipmentPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPaymentPageWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_PAYMENT_PAGE_WIDGETS, function () {
            return $this->getPaymentPageWidgetPlugins();
        });

        return $container;
    }

    /**
     * @return array<string>
     */
    protected function getPaymentPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSuccessPageWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_SUCCESS_PAGE_WIDGETS, function () {
            return $this->getSuccessPageWidgetPlugins();
        });

        return $container;
    }

    /**
     * @return array<string>
     */
    protected function getSuccessPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new CheckoutPageToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new CheckoutPageToLocaleClientBridge(
                $container->getLocator()->locale()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSubFormFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_SUB_FORM_FILTERS, function () {
            return $this->getSubFormFilterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface>
     */
    protected function getSubFormFilterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutAddressStepEnterPreCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CHECKOUT_ADDRESS_STEP_ENTER_PRE_CHECK, function () {
            return $this->getCheckoutAddressStepEnterPreCheckPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepEnterPreCheckPluginInterface>
     */
    protected function getCheckoutAddressStepEnterPreCheckPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutShipmentStepEnterPreCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CHECKOUT_SHIPMENT_STEP_ENTER_PRE_CHECK, function () {
            return $this->getCheckoutShipmentStepEnterPreCheckPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepEnterPreCheckPluginInterface>
     */
    protected function getCheckoutShipmentStepEnterPreCheckPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutPaymentStepEnterPreCheckPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CHECKOUT_PAYMENT_STEP_ENTER_PRE_CHECK, function () {
            return $this->getCheckoutPaymentStepEnterPreCheckPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutPaymentStepEnterPreCheckPluginInterface>
     */
    protected function getCheckoutPaymentStepEnterPreCheckPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShipmentService(Container $container): Container
    {
        $container->set(static::SERVICE_SHIPMENT, function (Container $container) {
            return new CheckoutPageToShipmentServiceBridge(
                $container->getLocator()->shipment()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerService(Container $container): Container
    {
        $container->set(static::SERVICE_CUSTOMER, function (Container $container): CheckoutPageToCustomerServiceInterface {
            return new CheckoutPageToCustomerServiceBridge($container->getLocator()->customer()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAddressStepExecutorAddressTransferExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_ADDRESS_STEP_EXECUTOR_ADDRESS_TRANSFER_EXPANDERS, function (): array {
            return $this->getAddressStepExecutorAddressExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface>
     */
    protected function getAddressStepExecutorAddressExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutAddressFormDataProviderPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CHECKOUT_ADDRESS_FORM_DATA_PROVIDER, function (): StepEngineFormDataProviderInterface {
            return $this->getCheckoutAddressFormDataProviderPlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    protected function getCheckoutAddressFormDataProviderPlugin(): StepEngineFormDataProviderInterface
    {
        trigger_error('CheckoutAddressFormDataProviderPlugin will not be provided after next major. ' .
            'You need to configure CheckoutAddressFormDataProviderPlugin ' .
            'in your own CheckoutPageDependencyProvider::getCheckoutAddressFormDataProviderPlugin() ' .
            'to be able to handle Address step in checkout.', E_USER_DEPRECATED);

        return new CheckoutAddressFormDataProviderPlugin();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface
     */
    protected function getCustomerStepHandler(): StepHandlerPluginInterface
    {
        trigger_error('CustomerStepHandler will not be provided after next major. ' .
            'Missing instance of %s! You need to configure CustomerStepHandler ' .
            'in your own CheckoutPageDependencyProvider::getCustomerStepHandler() ' .
            'to be able to handle Customer step in checkout.', E_USER_DEPRECATED);

        return new CustomerStepHandler();
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutStepResolverStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CHECKOUT_STEP_RESOLVER_STRATEGY, function () {
            return $this->getCheckoutStepResolverStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface>
     */
    protected function getCheckoutStepResolverStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutPageStepEnginePreRenderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CHECKOUT_PAGE_STEP_ENGINE_PRE_RENDER, function (): array {
            return $this->getCheckoutPageStepEnginePreRenderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\StepEngine\CheckoutPageStepEnginePreRenderPluginInterface>
     */
    protected function getCheckoutPageStepEnginePreRenderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPaymentCollectionExtenderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PAYMENT_COLLECTION_EXTENDER, function () {
            return $this->getPaymentCollectionExtenderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\PaymentCollectionExtenderPluginInterface>
     */
    protected function getPaymentCollectionExtenderPlugins(): array
    {
        return [];
    }
}
