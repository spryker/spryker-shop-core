<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Checkout\CheckoutDependencyProvider as SprykerCheckoutDependencyProvider;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCartClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientBridge;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToUtilValidateServiceBridge;
use SprykerShop\Yves\CheckoutPage\Plugin\CheckoutBreadcrumbPlugin;
use SprykerShop\Yves\CheckoutPage\Plugin\ShipmentFormDataProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Plugin\ShipmentHandlerPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientBridge;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientInterface;
use SprykerShop\Yves\CustomerPage\Plugin\CustomerStepHandler;
use SprykerShop\Yves\MoneyWidget\Plugin\MoneyPlugin;
use Symfony\Component\Form\FormTypeInterface;

class CheckoutPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_QUOTE = 'CLIENT_QUOTE';
    const CLIENT_CALCULATION = 'CLIENT_CALCULATION';
    const CLIENT_CHECKOUT = 'CLIENT_CHECKOUT';
    const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    const CLIENT_CART = 'CLIENT_CART';
    const CLIENT_SHIPMENT = 'CLIENT_SHIPMENT';
    const CLIENT_GLOSSARY = 'CLIENT_GLOSSARY';
    const CLIENT_PRICE = 'CLIENT_PRICE';

    const STORE = 'STORE';

    const SERVICE_UTIL_VALIDATE = 'SERVICE_UTIL_VALIDATE';

    const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    const PLUGIN_CUSTOMER_STEP_HANDLER = 'PLUGIN_CUSTOMER_STEP_HANDLER';
    const PLUGIN_SHIPMENT_STEP_HANDLER = 'PLUGIN_SHIPMENT_STEP_HANDLER';
    const PLUGIN_SHIPMENT_HANDLER = 'PLUGIN_SHIPMENT_HANDLER';
    const PLUGIN_SHIPMENT_FORM_DATA_PROVIDER = 'PLUGIN_SHIPMENT_FORM_DATA_PROVIDER';
    const PLUGIN_CHECKOUT_BREADCRUMB = 'PLUGIN_CHECKOUT_BREADCRUMB';
    const PLUGIN_MONEY = 'PLUGIN_MONEY';
    const PLUGIN_CUSTOMER_PAGE_WIDGETS = 'PLUGIN_CUSTOMER_PAGE_WIDGETS';
    const PLUGIN_ADDRESS_PAGE_WIDGETS = 'PLUGIN_ADDRESS_PAGE_WIDGETS';
    const PLUGIN_SHIPMENT_PAGE_WIDGETS = 'PLUGIN_SHIPMENT_PAGE_WIDGETS';
    const PLUGIN_PAYMENT_PAGE_WIDGETS = 'PLUGIN_PAYMENT_PAGE_WIDGETS';
    const PLUGIN_SUMMARY_PAGE_WIDGETS = 'PLUGIN_SUMMARY_PAGE_WIDGETS';
    const PLUGIN_SUCCESS_PAGE_WIDGETS = 'PLUGIN_SUCCESS_PAGE_WIDGETS';

    const PAYMENT_METHOD_HANDLER = SprykerCheckoutDependencyProvider::PAYMENT_METHOD_HANDLER; // constant value must be BC because of dependency injector
    const PAYMENT_SUB_FORMS = SprykerCheckoutDependencyProvider::PAYMENT_SUB_FORMS;  // constant value must be BC because of dependency injector
    const CUSTOMER_STEP_SUB_FORMS = 'CUSTOMER_STEP_SUB_FORMS';
    const ADDRESS_STEP_SUB_FORMS = 'ADDRESS_STEP_SUB_FORMS';
    const ADDRESS_STEP_FORM_DATA_PROVIDER = 'ADDRESS_STEP_FORM_DATA_PROVIDER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addQuoteClient($container);
        $container = $this->addCalculationClient($container);
        $container = $this->addCheckoutClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addShipmentClient($container);
        $container = $this->addGlossaryClient($container);
        $container = $this->addPriceClient($container);

        $container = $this->addApplication($container);
        $container = $this->provideStore($container);
        $container = $this->addUtilValidateService($container);

        $container = $this->addSubFormPluginCollection($container);
        $container = $this->addPaymentMethodHandlerPluginCollection($container);
        $container = $this->AddCustomerStepHandlerPlugin($container);
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

        $container = $this->addCustomerStepSubForms($container);
        $container = $this->addAddressStepSubForms($container);
        $container = $this->addAddressStepFormDataProvider($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilValidateService(Container $container)
    {
        $container[static::SERVICE_UTIL_VALIDATE] = function (Container $container) {
            return new CheckoutPageToUtilValidateServiceBridge($container->getLocator()->utilValidate()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutBreadcrumbPlugin(Container $container)
    {
        $container[self::PLUGIN_CHECKOUT_BREADCRUMB] = function () {
            return new CheckoutBreadcrumbPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container[self::CLIENT_QUOTE] = function () use ($container) {
            return new CheckoutPageToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCalculationClient(Container $container): Container
    {
        $container[self::CLIENT_CALCULATION] = function (Container $container) {
            return new CheckoutPageToCalculationClientBridge($container->getLocator()->calculation()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCheckoutClient(Container $container): Container
    {
        $container[self::CLIENT_CHECKOUT] = function (Container $container) {
            return new CheckoutPageToCheckoutClientBridge($container->getLocator()->checkout()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[self::CLIENT_CUSTOMER] = function (Container $container) {
            return new CheckoutPageToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container[self::CLIENT_CART] = function (Container $container) {
            return new CheckoutPageToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShipmentClient(Container $container): Container
    {
        $container[self::CLIENT_SHIPMENT] = function (Container $container) {
            return new CheckoutPageToShipmentClientBridge($container->getLocator()->shipment()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGlossaryClient(Container $container): Container
    {
        $container[self::CLIENT_GLOSSARY] = function (Container $container) {
            return new CheckoutPageToGlossaryClientBridge($container->getLocator()->glossary()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPriceClient(Container $container): Container
    {
        $container[self::CLIENT_PRICE] = function (Container $container) {
            return new CheckoutPageToPriceClientBridge($container->getLocator()->price()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container): Container
    {
        $container[static::PLUGIN_MONEY] = function () {
            return new MoneyPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerStepSubForms(Container $container): Container
    {
        $container[self::CUSTOMER_STEP_SUB_FORMS] = function () {
            return $this->getCustomerStepSubForms();
        };

        return $container;
    }

    /**
     * @return FormTypeInterface[]
     */
    protected function getCustomerStepSubForms()
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
        $container[self::ADDRESS_STEP_SUB_FORMS] = function () {
            return $this->getAddressStepSubForms();
        };

        return $container;
    }

    /**
     * @return FormTypeInterface[]
     */
    protected function getAddressStepSubForms()
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAddressStepFormDataProvider(Container $container): Container
    {
        $container[self::ADDRESS_STEP_FORM_DATA_PROVIDER] = function (Container $container) {
            return $this->getAddressStepFormDataProvider($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return StepEngineFormDataProviderInterface|null
     */
    protected function getAddressStepFormDataProvider(Container $container)
    {
        return null;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSubFormPluginCollection(Container $container): Container
    {
        $container[self::PAYMENT_SUB_FORMS] = function () {
            return new SubFormPluginCollection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPaymentMethodHandlerPluginCollection(Container $container): Container
    {
        $container[self::PAYMENT_METHOD_HANDLER] = function () {
            return new StepHandlerPluginCollection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function AddCustomerStepHandlerPlugin(Container $container): Container
    {
        $container[self::PLUGIN_CUSTOMER_STEP_HANDLER] = function () {
            return new CustomerStepHandler();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShipmentHandlerPluginCollection(Container $container): Container
    {
        $container[self::PLUGIN_SHIPMENT_HANDLER] = function () {
            $shipmentHandlerPlugins = new StepHandlerPluginCollection();
            $shipmentHandlerPlugins->add(new ShipmentHandlerPlugin(), self::PLUGIN_SHIPMENT_STEP_HANDLER);

            return $shipmentHandlerPlugins;
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShipmentFormDataProviderPlugin(Container $container): Container
    {
        $container[self::PLUGIN_SHIPMENT_FORM_DATA_PROVIDER] = function () {
            return new ShipmentFormDataProviderPlugin();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container[self::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();
            return $pimplePlugin->getApplication();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSummaryPageWidgetPlugins(Container $container): Container
    {
        $container[self::PLUGIN_SUMMARY_PAGE_WIDGETS] = function () {
            return $this->getSummaryPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
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
        $container[self::PLUGIN_CUSTOMER_PAGE_WIDGETS] = function () {
            return $this->getCustomerPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
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
        $container[self::PLUGIN_ADDRESS_PAGE_WIDGETS] = function () {
            return $this->getAddressPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
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
        $container[self::PLUGIN_SHIPMENT_PAGE_WIDGETS] = function () {
            return $this->getShipmentPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
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
        $container[self::PLUGIN_PAYMENT_PAGE_WIDGETS] = function () {
            return $this->getPaymentPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
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
        $container[self::PLUGIN_SUCCESS_PAGE_WIDGETS] = function () {
            return $this->getSuccessPageWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getSuccessPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param Container $container
     *
     * @return CustomerPageToCustomerClientInterface
     */
    protected function getCustomerClient(Container $container)
    {
        return new CustomerPageToCustomerClientBridge($container->getLocator()->customer()->client());
    }

    /**
     * @return Store
     */
    protected function getStore()
    {
        return Store::getInstance();
    }
}
