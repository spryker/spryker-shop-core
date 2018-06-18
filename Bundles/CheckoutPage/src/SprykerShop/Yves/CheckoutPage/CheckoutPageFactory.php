<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use SprykerShop\Yves\CheckoutPage\Form\DataProvider\ShipmentFormDataProvider;
use SprykerShop\Yves\CheckoutPage\Form\FormFactory;
use SprykerShop\Yves\CheckoutPage\Handler\ShipmentHandler;
use SprykerShop\Yves\CheckoutPage\Process\StepFactory;

class CheckoutPageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Process\StepEngineInterface
     */
    public function createCheckoutProcess()
    {
        return $this->createStepFactory()->createStepEngine(
            $this->createStepFactory()->createStepCollection()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\StepFactory
     */
    public function createStepFactory()
    {
        return new StepFactory();
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Form\FormFactory
     */
    public function createCheckoutFormFactory()
    {
        return new FormFactory();
    }

    /**
     * @return string[]
     */
    public function getCustomerPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_CUSTOMER_PAGE_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getAddressPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_ADDRESS_PAGE_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getShipmentPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_SHIPMENT_PAGE_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getPaymentPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_PAYMENT_PAGE_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getSummaryPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_SUMMARY_PAGE_WIDGETS);
    }

    /**
     * @return string[]
     */
    public function getSuccessPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_SUCCESS_PAGE_WIDGETS);
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected function getUrlGenerator()
    {
        return $this->getApplication()['url_generator'];
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getApplication()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface
     */
    protected function getCalculationClient(): CheckoutPageToCalculationClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected function getFlashMessenger()
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Plugin\CheckoutBreadcrumbPlugin
     */
    public function getCheckoutBreadcrumbPlugin()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_CHECKOUT_BREADCRUMB);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Form\DataProvider\ShipmentFormDataProvider
     */
    public function createShipmentDataProvider()
    {
        return new ShipmentFormDataProvider(
            $this->getShipmentClient(),
            $this->getGlossaryClient(),
            $this->getStore(),
            $this->getMoneyPlugin()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Handler\ShipmentHandlerInterface
     */
    public function createShipmentHandler()
    {
        return new ShipmentHandler($this->getShipmentClient(), $this->getPriceClient());
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface
     */
    public function getShipmentClient(): CheckoutPageToShipmentClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_SHIPMENT);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryClientInterface
     */
    public function getGlossaryClient(): CheckoutPageToGlossaryClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_GLOSSARY);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface
     */
    public function getQuoteClient(): CheckoutPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected function getMoneyPlugin()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_MONEY);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::STORE);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPriceClientInterface
     */
    protected function getPriceClient(): CheckoutPageToPriceClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function createPaymentMethodSubForms()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PAYMENT_SUB_FORMS);
    }
}
