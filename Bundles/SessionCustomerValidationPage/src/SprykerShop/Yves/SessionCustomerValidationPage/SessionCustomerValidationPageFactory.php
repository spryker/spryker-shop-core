<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionCustomerValidationPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToSessionClientInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\EventSubscriber\SaveCustomerSessionEventSubscriber;
use SprykerShop\Yves\SessionCustomerValidationPage\Extender\SessionCustomerValidationSecurityExtender;
use SprykerShop\Yves\SessionCustomerValidationPage\Extender\SessionCustomerValidationSecurityExtenderInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\FirewallListener\ValidateCustomerSessionListener;
use SprykerShop\Yves\SessionCustomerValidationPage\FirewallListener\ValidateCustomerSessionListenerInterface;
use SprykerShop\Yves\SessionCustomerValidationPage\Updater\CustomerSessionUpdater;
use SprykerShop\Yves\SessionCustomerValidationPage\Updater\CustomerSessionUpdaterInterface;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @method \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig getConfig()
 */
class SessionCustomerValidationPageFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSaveCustomerSessionEventSubscriber(): EventSubscriberInterface
    {
        return new SaveCustomerSessionEventSubscriber(
            $this->getCustomerClient(),
            $this->getCustomerSessionSaverPlugin(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SessionCustomerValidationPage\Updater\CustomerSessionUpdaterInterface
     */
    public function createCustomerSessionUpdater(): CustomerSessionUpdaterInterface
    {
        return new CustomerSessionUpdater(
            $this->getSessionClient(),
            $this->getCustomerSessionSaverPlugin(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SessionCustomerValidationPage\FirewallListener\ValidateCustomerSessionListenerInterface
     */
    public function createValidateCustomerSessionListener(): ValidateCustomerSessionListenerInterface
    {
        return new ValidateCustomerSessionListener(
            $this->getCustomerClient(),
            $this->getCustomerSessionValidatorPlugin(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SessionCustomerValidationPage\Extender\SessionCustomerValidationSecurityExtenderInterface
     */
    public function createSessionCustomerValidationSecurityExtender(): SessionCustomerValidationSecurityExtenderInterface
    {
        return new SessionCustomerValidationSecurityExtender(
            $this->createValidateCustomerSessionListener(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface
     */
    public function getCustomerSessionSaverPlugin(): CustomerSessionSaverPluginInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationPageDependencyProvider::PLUGIN_CUSTOMER_SESSION_SAVER);
    }

    /**
     * @return \SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface
     */
    protected function getCustomerSessionValidatorPlugin(): CustomerSessionValidatorPluginInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationPageDependencyProvider::PLUGIN_CUSTOMER_SESSION_VALIDATOR);
    }

    /**
     * @return \SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToCustomerClientInterface
     */
    public function getCustomerClient(): SessionCustomerValidationPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\SessionCustomerValidationPage\Dependency\Client\SessionCustomerValidationPageToSessionClientInterface
     */
    public function getSessionClient(): SessionCustomerValidationPageToSessionClientInterface
    {
        return $this->getProvidedDependency(SessionCustomerValidationPageDependencyProvider::CLIENT_SESSION);
    }
}
