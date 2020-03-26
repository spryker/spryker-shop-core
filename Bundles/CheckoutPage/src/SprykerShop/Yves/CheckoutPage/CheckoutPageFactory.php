<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CheckoutPage\Form\DataProvider\ShipmentFormDataProvider;
use SprykerShop\Yves\CheckoutPage\Form\Filter\SubFormFilter;
use SprykerShop\Yves\CheckoutPage\Form\Filter\SubFormFilterInterface;
use SprykerShop\Yves\CheckoutPage\Form\FormFactory;
use SprykerShop\Yves\CheckoutPage\Process\StepFactory;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
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
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface
     */
    public function getCheckoutClient(): CheckoutPageToCheckoutClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CHECKOUT);
    }

    /**
     * @return string[]
     */
    public function getSummaryPageWidgetPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_SUMMARY_PAGE_WIDGETS);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Form\DataProvider\ShipmentFormDataProvider
     */
    public function createShipmentDataProvider()
    {
        return new ShipmentFormDataProvider(
            $this->getShipmentClient(),
            $this->getGlossaryStorageClient(),
            $this->getStore(),
            $this->getMoneyPlugin(),
            $this->getShipmentService(),
            $this->getConfig(),
            $this->getProductBundleClient(),
            $this->getCheckoutShipmentStepPreGroupItemsByShipmentPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToShipmentClientInterface
     */
    public function getShipmentClient(): CheckoutPageToShipmentClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_SHIPMENT);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): CheckoutPageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface
     */
    public function getProductBundleClient(): CheckoutPageToProductBundleClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_PRODUCT_BUNDLE);
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
    public function getMoneyPlugin()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_MONEY);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::STORE);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface
     */
    public function getShipmentService(): CheckoutPageToShipmentServiceInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function getPaymentMethodSubForms()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PAYMENT_SUB_FORMS);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Form\Filter\SubFormFilterInterface
     */
    public function createSubFormFilter(): SubFormFilterInterface
    {
        return new SubFormFilter(
            $this->getSubFormFilterPlugins(),
            $this->getQuoteClient()
        );
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface[]
     */
    protected function getSubFormFilterPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_SUB_FORM_FILTERS);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepPreGroupItemsByShipmentPluginInterface[]
     */
    public function getCheckoutShipmentStepPreGroupItemsByShipmentPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGINS_CHECKOUT_SHIPMENT_FORM_PRE_GROUP_ITEMS_BY_SHIPMENT);
    }
}
