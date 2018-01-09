<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Process\StepBreadcrumbGenerator;
use Spryker\Yves\StepEngine\Process\StepCollection;
use Spryker\Yves\StepEngine\Process\StepCollectionInterface;
use Spryker\Yves\StepEngine\Process\StepEngine;
use SprykerShop\Yves\CheckoutPage\CheckoutPageDependencyProvider;
use SprykerShop\Yves\CheckoutPage\DataContainer\DataContainer;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCartClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\CustomerStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\EntryStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PaymentStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PlaceOrderStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\SuccessStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\SummaryStep;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use SprykerShop\Yves\HomePage\Plugin\Provider\HomePageControllerProvider;

class StepFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection
     */
    public function createPaymentMethodHandler()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PAYMENT_METHOD_HANDLER);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\DataContainer\DataContainer
     */
    protected function createDataContainer()
    {
        return new DataContainer($this->getQuoteClient());
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface
     */
    protected function getQuoteClient(): CheckoutPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     *
     * @return \Spryker\Yves\StepEngine\Process\StepEngine
     */
    public function createStepEngine(StepCollectionInterface $stepCollection)
    {
        return new StepEngine($stepCollection, $this->createDataContainer(), $this->createStepBreadcrumbGenerator());
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepCollectionInterface
     */
    public function createStepCollection()
    {
        $stepCollection = new StepCollection(
            $this->getUrlGenerator(),
            CheckoutPageControllerProvider::CHECKOUT_ERROR
        );

        $stepCollection
            ->addStep($this->createEntryStep())
            ->addStep($this->createCustomerStep())
            ->addStep($this->createAddressStep())
            ->addStep($this->createShipmentStep())
            ->addStep($this->createPaymentStep())
            ->addStep($this->createSummaryStep())
            ->addStep($this->createPlaceOrderStep())
            ->addStep($this->createSuccessStep());

        return $stepCollection;
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\EntryStep
     */
    protected function createEntryStep()
    {
        return new EntryStep(
            CheckoutPageControllerProvider::CHECKOUT_INDEX,
            HomePageControllerProvider::ROUTE_HOME
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\CustomerStep
     */
    protected function createCustomerStep()
    {
        return new CustomerStep(
            $this->getCustomerClient(),
            $this->getCustomerStepHandler(),
            CheckoutPageControllerProvider::CHECKOUT_CUSTOMER,
            HomePageControllerProvider::ROUTE_HOME,
            $this->getApplication()->path(CustomerPageControllerProvider::ROUTE_LOGOUT)
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep
     */
    protected function createAddressStep()
    {
        return new AddressStep(
            $this->getCustomerClient(),
            $this->getCalculationClient(),
            CheckoutPageControllerProvider::CHECKOUT_ADDRESS,
            HomePageControllerProvider::ROUTE_HOME
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep
     */
    protected function createShipmentStep()
    {
        return new ShipmentStep(
            $this->getCalculationClient(),
            $this->getShipmentPlugins(),
            CheckoutPageControllerProvider::CHECKOUT_SHIPMENT,
            HomePageControllerProvider::ROUTE_HOME
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface
     */
    public function getProductBundleClient(): CheckoutPageToProductBundleClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_PRODUCT_BUNDLE);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection
     */
    public function getShipmentPlugins()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_SHIPMENT_HANDLER);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\PaymentStep
     */
    protected function createPaymentStep()
    {
        return new PaymentStep(
            $this->createPaymentMethodHandler(),
            CheckoutPageControllerProvider::CHECKOUT_PAYMENT,
            HomePageControllerProvider::ROUTE_HOME,
            $this->getFlashMessenger()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\SummaryStep
     */
    protected function createSummaryStep()
    {
        return new SummaryStep(
            $this->getProductBundleClient(),
            CheckoutPageControllerProvider::CHECKOUT_SUMMARY,
            HomePageControllerProvider::ROUTE_HOME
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\PlaceOrderStep
     */
    protected function createPlaceOrderStep()
    {
        return new PlaceOrderStep(
            $this->getCheckoutClient(),
            $this->getFlashMessenger(),
            CheckoutPageControllerProvider::CHECKOUT_PLACE_ORDER,
            HomePageControllerProvider::ROUTE_HOME,
            [
                'payment failed' => CheckoutPageControllerProvider::CHECKOUT_PAYMENT,
            ]
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\SuccessStep
     */
    protected function createSuccessStep()
    {
        return new SuccessStep(
            $this->getCustomerClient(),
            $this->getCartClient(),
            CheckoutPageControllerProvider::CHECKOUT_SUCCESS,
            HomePageControllerProvider::ROUTE_HOME
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface
     */
    protected function getCustomerStepHandler()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_CUSTOMER_STEP_HANDLER);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected function getFlashMessenger()
    {
        return $this->getApplication()['flash_messenger'];
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
    public function getCalculationClient(): CheckoutPageToCalculationClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface
     */
    public function getCheckoutClient(): CheckoutPageToCheckoutClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CHECKOUT);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCartClientInterface
     */
    public function getCartClient(): CheckoutPageToCartClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepBreadcrumbGeneratorInterface
     */
    public function createStepBreadcrumbGenerator()
    {
        return new StepBreadcrumbGenerator();
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCustomerClientInterface
     */
    protected function getCustomerClient(): CheckoutPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CUSTOMER);
    }
}
