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
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\AddressSaverWithoutMultiShipment;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\CustomerStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\EntryStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PaymentStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PlaceOrderStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\SuccessStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\SummaryStep;
use SprykerShop\Yves\CheckoutPage\StrategyResolver\AddressStep\AddressStepStrategyResolver;
use SprykerShop\Yves\CheckoutPage\StrategyResolver\AddressStep\AddressStepStrategyResolverInterface;
use SprykerShop\Yves\CheckoutPage\StrategyResolver\ShipmentStep\ShipmentStepStrategyResolver;
use SprykerShop\Yves\CheckoutPage\StrategyResolver\ShipmentStep\ShipmentStepStrategyResolverInterface;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use SprykerShop\Yves\HomePage\Plugin\Provider\HomePageControllerProvider;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\SaverInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\AddressSaver;
use SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\PostConditionChecker as AddressStepPostConditionChecker;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\PostConditionCheckerWithoutMultiShipment as AddressStepPostConditionCheckerWithoutMultiShipment;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep\PostConditionChecker as ShipmentStepPostConditionChecker;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep\PostConditionCheckerWithoutMultiShipment as ShipmentStepPostConditionCheckerWithoutMultiShipment;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class StepFactory extends AbstractFactory
{
    protected const ERROR_CODE_GENERAL_FAILURE = 399;
    protected const ROUTE_CART = 'cart';

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection
     */
    public function getPaymentMethodHandler()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PAYMENT_METHOD_HANDLER);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\DataContainer\DataContainer
     */
    public function createDataContainer()
    {
        return new DataContainer($this->getQuoteClient());
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface
     */
    public function getQuoteClient(): CheckoutPageToQuoteClientInterface
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
    public function createEntryStep()
    {
        return new EntryStep(
            CheckoutPageControllerProvider::CHECKOUT_INDEX,
            HomePageControllerProvider::ROUTE_HOME
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\CustomerStep
     */
    public function createCustomerStep()
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
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep
     */
    public function createAddressStep(): AddressStep
    {
        return new AddressStep(
            $this->getCustomerClient(),
            $this->getCalculationClient(),
            CheckoutPageControllerProvider::CHECKOUT_ADDRESS,
            HomePageControllerProvider::ROUTE_HOME,
            $this->createAddressStepStrategyResolver()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep
     */
    public function createShipmentStep()
    {
        return new ShipmentStep(
            $this->getCalculationClient(),
            $this->getShipmentPlugins(),
            CheckoutPageControllerProvider::CHECKOUT_SHIPMENT,
            HomePageControllerProvider::ROUTE_HOME,
            $this->createShipmentStepStrategyResolver()
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
    public function createPaymentStep()
    {
        return new PaymentStep(
            $this->getPaymentMethodHandler(),
            CheckoutPageControllerProvider::CHECKOUT_PAYMENT,
            HomePageControllerProvider::ROUTE_HOME,
            $this->getFlashMessenger(),
            $this->getCalculationClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\SummaryStep
     */
    public function createSummaryStep()
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
    public function createPlaceOrderStep()
    {
        return new PlaceOrderStep(
            $this->getCheckoutClient(),
            $this->getFlashMessenger(),
            $this->getStore()->getCurrentLocale(),
            $this->getGlossaryStorageClient(),
            CheckoutPageControllerProvider::CHECKOUT_PLACE_ORDER,
            HomePageControllerProvider::ROUTE_HOME,
            [
                static::ERROR_CODE_GENERAL_FAILURE => self::ROUTE_CART,
                'payment failed' => CheckoutPageControllerProvider::CHECKOUT_PAYMENT,
                'shipment failed' => CheckoutPageControllerProvider::CHECKOUT_SHIPMENT,
            ]
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\SuccessStep
     */
    public function createSuccessStep()
    {
        return new SuccessStep(
            $this->getCustomerClient(),
            $this->getCartClient(),
            $this->getConfig(),
            CheckoutPageControllerProvider::CHECKOUT_SUCCESS,
            HomePageControllerProvider::ROUTE_HOME
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface
     */
    public function getCustomerStepHandler()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_CUSTOMER_STEP_HANDLER);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    public function getFlashMessenger()
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    public function getUrlGenerator()
    {
        return $this->getApplication()['url_generator'];
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication()
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
    public function getCustomerClient(): CheckoutPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::STORE);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): CheckoutPageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\SaverInterface
     */
    public function createAddressStepSaver(): SaverInterface
    {
        return new AddressSaver($this->getCustomerClient());
    }

    /**
     * @deprecated Use createAddressStepSaver() instead.
     *
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\SaverInterface
     */
    public function createAddressStepSaverWithoutMultipleShipment(): SaverInterface
    {
        return new AddressSaverWithoutMultiShipment($this->getCustomerClient());
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface
     */
    public function createAddressStepPostConditionChecker(): PostConditionCheckerInterface
    {
        return new AddressStepPostConditionChecker();
    }

    /**
     * @deprecated Use createAddressStepPostConditionChecker() instead.
     *
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface
     */
    public function createAddressStepPostConditionCheckerWithoutMultipleShipment(): PostConditionCheckerInterface
    {
        return new AddressStepPostConditionCheckerWithoutMultiShipment();
    }

    /**
     * @deprecated Remove after multiple shipment will be released. Use $this->createCheckoutCustomerOrderSaverWithMultiShippingAddress() instead.
     *
     * @return \SprykerShop\Yves\CheckoutPage\StrategyResolver\AddressStep\AddressStepStrategyResolverInterface
     */
    public function createAddressStepStrategyResolver(): AddressStepStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addAddressStepSaverWithoutMultipleShippingAddress($strategyContainer);
        $strategyContainer = $this->addAddressStepSaverWithMultipleShippingAddress($strategyContainer);

        $strategyContainer = $this->addAddressStepPostConditionCheckerWithoutMultipleShipment($strategyContainer);
        $strategyContainer = $this->addAddressStepPostConditionCheckerWithMultipleShipment($strategyContainer);

        return new AddressStepStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Remove after multiple shipment will be released.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addAddressStepSaverWithoutMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[AddressStepStrategyResolverInterface::STRATEGY_KEY_SAVER_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createAddressStepSaverWithoutMultipleShipment();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Remove after multiple shipment will be released.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addAddressStepSaverWithMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[AddressStepStrategyResolverInterface::STRATEGY_KEY_SAVER_WITH_MULTI_SHIPMENT] = function () {
            return $this->createAddressStepSaver();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Remove after multiple shipment will be released.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addAddressStepPostConditionCheckerWithoutMultipleShipment(array $strategyContainer): array
    {
        $strategyContainer[AddressStepStrategyResolverInterface::STRATEGY_KEY_POST_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createAddressStepPostConditionCheckerWithoutMultipleShipment();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Remove after multiple shipment will be released.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addAddressStepPostConditionCheckerWithMultipleShipment(array $strategyContainer): array
    {
        $strategyContainer[AddressStepStrategyResolverInterface::STRATEGY_KEY_POST_CONDITION_CHECKER_WITH_MULTI_SHIPMENT] = function () {
            return $this->createAddressStepPostConditionChecker();
        };

        return $strategyContainer;
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface
     */
    public function createShipmentStepPostConditionChecker(): PostConditionCheckerInterface
    {
        return new ShipmentStepPostConditionChecker();
    }

    /**
     * @deprecated Use createShipmentStepPostConditionChecker() instead.
     *
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\BaseActions\PostConditionCheckerInterface
     */
    public function createShipmentStepPostConditionCheckerWithoutMultipleShipment(): PostConditionCheckerInterface
    {
        return new ShipmentStepPostConditionCheckerWithoutMultiShipment();
    }

    /**
     * @deprecated Remove after multiple shipment will be released. Use $this->createCheckoutCustomerOrderSaverWithMultiShippingAddress() instead.
     *
     * @return \SprykerShop\Yves\CheckoutPage\StrategyResolver\ShipmentStep\ShipmentStepStrategyResolverInterface
     */
    public function createShipmentStepStrategyResolver(): ShipmentStepStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addShipmentStepPostConditionCheckerWithoutMultipleShipment($strategyContainer);
        $strategyContainer = $this->addShipmentStepPostConditionCheckerWithMultipleShipment($strategyContainer);

        return new ShipmentStepStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Remove after multiple shipment will be released.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addShipmentStepPostConditionCheckerWithoutMultipleShipment(array $strategyContainer): array
    {
        $strategyContainer[ShipmentStepStrategyResolverInterface::STRATEGY_KEY_POST_CONDITION_CHECKER_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createShipmentStepPostConditionCheckerWithoutMultipleShipment();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Remove after multiple shipment will be released.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addShipmentStepPostConditionCheckerWithMultipleShipment(array $strategyContainer): array
    {
        $strategyContainer[ShipmentStepStrategyResolverInterface::STRATEGY_KEY_POST_CONDITION_CHECKER_WITH_MULTI_SHIPMENT] = function () {
            return $this->createShipmentStepPostConditionChecker();
        };

        return $strategyContainer;
    }
}
