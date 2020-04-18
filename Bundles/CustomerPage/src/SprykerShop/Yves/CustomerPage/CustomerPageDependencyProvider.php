<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToCustomerClientBridge;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToProductBundleClientBridge;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToQuoteClientBridge;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToSalesClientBridge;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToShipmentClientBridge;
use SprykerShop\Yves\CustomerPage\Dependency\Client\CustomerPageToShipmentClientInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToCustomerServiceBridge;
use SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToCustomerServiceInterface;
use SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToShipmentServiceBridge;
use SprykerShop\Yves\CustomerPage\Dependency\Service\CustomerPageToUtilValidateServiceBridge;
use SprykerShop\Yves\CustomerPage\Plugin\AuthenticationHandler;
use SprykerShop\Yves\CustomerPage\Plugin\GuestCheckoutAuthenticationHandlerPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\LoginCheckoutAuthenticationHandlerPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\RegistrationCheckoutAuthenticationHandlerPlugin;

class CustomerPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_SALES = 'CLIENT_SALES';
    public const CLIENT_SHIPMENT = 'CLIENT_SHIPMENT';
    public const CLIENT_PRODUCT_BUNDLE = 'CLIENT_PRODUCT_BUNDLE';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    public const PLUGIN_AFTER_CUSTOMER_AUTHENTICATION_SUCCESS = 'PLUGIN_AFTER_CUSTOMER_AUTHENTICATION_SUCCESS';
    public const PLUGIN_AFTER_LOGIN_CUSTOMER_REDIRECT = 'PLUGIN_AFTER_LOGIN_CUSTOMER_REDIRECT';
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    public const PLUGIN_AUTHENTICATION_HANDLER = 'PLUGIN_AUTHENTICATION_HANDLER';
    public const PLUGIN_CUSTOMER_MENU_ITEM_WIDGETS = 'PLUGIN_CUSTOMER_MENU_ITEM_WIDGETS';
    public const PLUGIN_CUSTOMER_ORDER_LIST_WIDGETS = 'PLUGIN_CUSTOMER_ORDER_LIST_WIDGETS';
    public const PLUGIN_CUSTOMER_ORDER_VIEW_WIDGETS = 'PLUGIN_CUSTOMER_ORDER_VIEW_WIDGETS';
    public const PLUGIN_CUSTOMER_OVERVIEW_WIDGETS = 'PLUGIN_CUSTOMER_OVERVIEW_WIDGETS';
    public const PLUGIN_GUEST_AUTHENTICATION_HANDLER = 'PLUGIN_GUEST_AUTHENTICATION_HANDLER';
    public const PLUGIN_LOGIN_AUTHENTICATION_HANDLER = 'PLUGIN_LOGIN_AUTHENTICATION_HANDLER';
    public const PLUGIN_PRE_REGISTRATION_CUSTOMER_TRANSFER_EXPANDER = 'PLUGIN_PRE_REGISTRATION_CUSTOMER_TRANSFER_EXPANDER';
    public const PLUGIN_REGISTRATION_AUTHENTICATION_HANDLER = 'PLUGIN_REGISTRATION_AUTHENTICATION_HANDLER';

    public const SERVICE_CUSTOMER = 'SERVICE_CUSTOMER';
    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';
    public const SERVICE_UTIL_VALIDATE = 'SERVICE_UTIL_VALIDATE';

    public const FLASH_MESSENGER = 'FLASH_MESSENGER';
    public const STORE = 'STORE';

    public const PLUGINS_ORDER_SEARCH_FORM_EXPANDER = 'PLUGINS_ORDER_SEARCH_FORM_EXPANDER';
    public const PLUGINS_ORDER_SEARCH_FORM_HANDLER = 'PLUGINS_ORDER_SEARCH_FORM_HANDLER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addProductBundleClient($container);
        $container = $this->addSalesClient($container);
        $container = $this->addShipmentClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addApplication($container);
        $container = $this->addAuthenticationHandlerPlugin($container);
        $container = $this->addLoginCheckoutAuthenticationHandlerPlugin($container);
        $container = $this->addGuestCheckoutAuthenticationHandlerPlugin($container);
        $container = $this->addRegistrationCheckoutAuthenticationHandlerPlugin($container);
        $container = $this->addFlashMessenger($container);
        $container = $this->addStore($container);
        $container = $this->addCustomerOverviewWidgetPlugins($container);
        $container = $this->addCustomerOrderListWidgetPlugins($container);
        $container = $this->addCustomerOrderViewWidgetPlugins($container);
        $container = $this->addCustomerMenuItemWidgetPlugins($container);
        $container = $this->addUtilValidateService($container);
        $container = $this->addPreRegistrationCustomerTransferExpanderPlugins($container);
        $container = $this->addAfterLoginCustomerRedirectPlugins($container);
        $container = $this->addAfterCustomerAuthenticationSuccessPlugins($container);
        $container = $this->addShipmentService($container);
        $container = $this->addCustomerService($container);
        $container = $this->addOrderSearchFormExpanderPlugins($container);
        $container = $this->addOrderSearchFormHandlerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container->set(static::STORE, function () {
            return Store::getInstance();
        });

        return $container;
    }

    /**
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
    protected function addAuthenticationHandlerPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_AUTHENTICATION_HANDLER, function () {
            return new AuthenticationHandler();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLoginCheckoutAuthenticationHandlerPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_LOGIN_AUTHENTICATION_HANDLER, function () {
            return new LoginCheckoutAuthenticationHandlerPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGuestCheckoutAuthenticationHandlerPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_GUEST_AUTHENTICATION_HANDLER, function () {
            return new GuestCheckoutAuthenticationHandlerPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRegistrationCheckoutAuthenticationHandlerPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_REGISTRATION_AUTHENTICATION_HANDLER, function () {
            return new RegistrationCheckoutAuthenticationHandlerPlugin();
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
        $container->set(static::FLASH_MESSENGER, function (Container $container) {
            return $container[static::PLUGIN_APPLICATION]['flash_messenger'];
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
            return new CustomerPageToCustomerClientBridge($container->getLocator()->customer()->client());
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
            return new CustomerPageToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSalesClient(Container $container): Container
    {
        $container->set(static::CLIENT_SALES, function (Container $container) {
            return new CustomerPageToSalesClientBridge($container->getLocator()->sales()->client());
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
            return new CustomerPageToProductBundleClientBridge($container->getLocator()->productBundle()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerOverviewWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CUSTOMER_OVERVIEW_WIDGETS, function () {
            return $this->getCustomerOverviewWidgetPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerOrderListWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CUSTOMER_ORDER_LIST_WIDGETS, function () {
            return $this->getCustomerOrderListWidgetPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerOrderViewWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CUSTOMER_ORDER_VIEW_WIDGETS, function () {
            return $this->getCustomerOrderViewWidgetPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerMenuItemWidgetPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_CUSTOMER_MENU_ITEM_WIDGETS, function () {
            return $this->getCustomerMenuItemWidgetPlugins();
        });

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
            return new CustomerPageToUtilValidateServiceBridge($container->getLocator()->utilValidate()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPreRegistrationCustomerTransferExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_PRE_REGISTRATION_CUSTOMER_TRANSFER_EXPANDER, function () {
            return $this->getPreRegistrationCustomerTransferExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\PreRegistrationCustomerTransferExpanderPluginInterface[]
     */
    protected function getPreRegistrationCustomerTransferExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAfterLoginCustomerRedirectPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_AFTER_LOGIN_CUSTOMER_REDIRECT, function () {
            return $this->getAfterLoginCustomerRedirectPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CustomerRedirectStrategyPluginInterface[]
     */
    protected function getAfterLoginCustomerRedirectPlugins(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected function getCustomerOrderListWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected function getCustomerOrderViewWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected function getCustomerMenuItemWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected function getCustomerOverviewWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAfterCustomerAuthenticationSuccessPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_AFTER_CUSTOMER_AUTHENTICATION_SUCCESS, function () {
            return $this->getAfterCustomerAuthenticationSuccessPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\AfterCustomerAuthenticationSuccessPluginInterface[]
     */
    protected function getAfterCustomerAuthenticationSuccessPlugins(): array
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
            return new CustomerPageToShipmentServiceBridge($container->getLocator()->shipment()->service());
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
        $container->set(static::SERVICE_CUSTOMER, function (Container $container): CustomerPageToCustomerServiceInterface {
            return new CustomerPageToCustomerServiceBridge($container->getLocator()->customer()->service());
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
        $container->set(static::CLIENT_SHIPMENT, function (Container $container): CustomerPageToShipmentClientInterface {
            return new CustomerPageToShipmentClientBridge($container->getLocator()->shipment()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addOrderSearchFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_SEARCH_FORM_EXPANDER, function () {
            return $this->getOrderSearchFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addOrderSearchFormHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ORDER_SEARCH_FORM_HANDLER, function () {
            return $this->getOrderSearchFormHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormExpanderPluginInterface[]
     */
    protected function getOrderSearchFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OrderSearchFormHandlerPluginInterface[]
     */
    protected function getOrderSearchFormHandlerPlugins(): array
    {
        return [];
    }
}
