<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CustomerPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\CustomerPage\Plugin\AuthenticationHandler;
use SprykerShop\Yves\CustomerPage\Plugin\GuestCheckoutAuthenticationHandlerPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\LoginCheckoutAuthenticationHandlerPlugin;
use SprykerShop\Yves\CustomerPage\Plugin\RegistrationCheckoutAuthenticationHandlerPlugin;

class CustomerPageDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_CUSTOMER = 'customer client';
    const CLIENT_SALES = 'client client';
    const PLUGIN_APPLICATION = 'application plugin';
    const PLUGIN_AUTHENTICATION_HANDLER = 'authentication plugin';
    const PLUGIN_LOGIN_AUTHENTICATION_HANDLER = 'login authentication plugin';
    const PLUGIN_GUEST_AUTHENTICATION_HANDLER = 'guest authentication plugin';
    const PLUGIN_REGISTRATION_AUTHENTICATION_HANDLER = 'registration authentication plugin';
    const FLASH_MESSENGER = 'flash messenger';
    const STORE = 'store';
    const PLUGIN_CUSTOMER_OVERVIEW_WIDGETS = 'PLUGIN_CUSTOMER_OVERVIEW_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addSalesClient($container);
        $container = $this->addApplication($container);
        $container = $this->addAuthenticationHandlerPlugin($container);
        $container = $this->addLoginCheckoutAuthenticationHandlerPlugin($container);
        $container = $this->addGuestCheckoutAuthenticationHandlerPlugin($container);
        $container = $this->addRegistrationCheckoutAuthenticationHandlerPlugin($container);
        $container = $this->addFlashMessenger($container);
        $container = $this->provideStore($container);
        $container = $this->addCustomerOverviewWidgetPlugins($container);

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
    protected function addAuthenticationHandlerPlugin(Container $container): Container
    {
        $container[self::PLUGIN_AUTHENTICATION_HANDLER] = function () {
            return new AuthenticationHandler();
        };
        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLoginCheckoutAuthenticationHandlerPlugin(Container $container): Container
    {
        $container[self::PLUGIN_LOGIN_AUTHENTICATION_HANDLER] = function () {
            return new LoginCheckoutAuthenticationHandlerPlugin();
        };
        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addGuestCheckoutAuthenticationHandlerPlugin(Container $container): Container
    {
        $container[self::PLUGIN_GUEST_AUTHENTICATION_HANDLER] = function () {
            return new GuestCheckoutAuthenticationHandlerPlugin();
        };
        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRegistrationCheckoutAuthenticationHandlerPlugin(Container $container): Container
    {
        $container[self::PLUGIN_REGISTRATION_AUTHENTICATION_HANDLER] = function () {
            return new RegistrationCheckoutAuthenticationHandlerPlugin();
        };
        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFlashMessenger(Container $container): Container
    {
        $container[self::FLASH_MESSENGER] = function (Container $container) {
            return $container[self::PLUGIN_APPLICATION]['flash_messenger'];
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
            return $container->getLocator()->customer()->client();
        };
        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSalesClient(Container $container): Container
    {
        $container[self::CLIENT_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->client();
        };
        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerOverviewWidgetPlugins(Container $container)
    {
        $container[static::PLUGIN_CUSTOMER_OVERVIEW_WIDGETS] = function (Container $container) {
            return $this->getCustomerOverviewWidgetPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return string[]
     */
    protected function getCustomerOverviewWidgetPlugins(Container $container): array
    {
        return [];
    }
}
