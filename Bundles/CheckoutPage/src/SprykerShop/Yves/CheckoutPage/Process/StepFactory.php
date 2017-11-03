<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CheckoutPage\Process;

use SprykerShop\Yves\CheckoutPage\DataContainer\DataContainer;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CheckoutPage\CheckoutPageDependencyProvider;
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
use Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper;
use Spryker\Yves\StepEngine\Process\StepBreadcrumbGenerator;
use Spryker\Yves\StepEngine\Process\StepCollection;
use Spryker\Yves\StepEngine\Process\StepCollectionInterface;
use Spryker\Yves\StepEngine\Process\StepEngine;
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
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutToQuoteInterface
     */
    protected function getQuoteClient()
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
            $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CUSTOMER),
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
            $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CUSTOMER),
            $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CALCULATION),
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
     * @return \Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper
     */
    public function createProductBundleGrouper()
    {
        return new ProductBundleGrouper();
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
            $this->createProductBundleGrouper(),
            $this->getCartClient(),
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
            $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CUSTOMER),
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
     * @return \Spryker\Client\Calculation\CalculationClient
     */
    public function getCalculationClient()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \Spryker\Client\Checkout\CheckoutClientInterface
     */
    public function getCheckoutClient()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CHECKOUT);
    }

    /**
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    public function getCartClient()
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
}
