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
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsChecker;
use SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\AddressStepExecutor;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\PostConditionChecker as AddressStepPostConditionChecker;
use SprykerShop\Yves\CheckoutPage\Process\Steps\CustomerStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\EntryStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PaymentStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PlaceOrderStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\Resolver\StepResolver;
use SprykerShop\Yves\CheckoutPage\Process\Steps\Resolver\StepResolverInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ShipmentStep\PostConditionChecker as ShipmentStepPostConditionChecker;
use SprykerShop\Yves\CheckoutPage\Process\Steps\StepExecutorInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\SuccessStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\SummaryStep;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use SprykerShop\Yves\HomePage\Plugin\Provider\HomePageControllerProvider;

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
        return new StepCollection(
            $this->getUrlGenerator(),
            CheckoutPageControllerProvider::CHECKOUT_ERROR
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface[]
     */
    public function getSteps(): array
    {
        return [
            $this->createEntryStep(),
            $this->createCustomerStep(),
            $this->createAddressStep(),
            $this->createShipmentStep(),
            $this->createPaymentStep(),
            $this->createSummaryStep(),
            $this->createPlaceOrderStep(),
            $this->createSuccessStep(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\Resolver\StepResolverInterface
     */
    public function createStepResolver(): StepResolverInterface
    {
        return new StepResolver(
            $this->getQuoteClient(),
            $this->getCheckoutStepResolverStrategyPlugins(),
            $this->getSteps(),
            $this->createStepCollection()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\EntryStep
     */
    public function createEntryStep()
    {
        return new EntryStep(
            static::ROUTE_CART,
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
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep
     */
    public function createAddressStep(): AddressStep
    {
        return new AddressStep(
            $this->getCalculationClient(),
            $this->createAddressStepExecutor(),
            $this->createAddressStepPostConditionChecker(),
            $this->getConfig(),
            CheckoutPageControllerProvider::CHECKOUT_ADDRESS,
            HomePageControllerProvider::ROUTE_HOME,
            $this->getCheckoutAddressStepEnterPreCheckPlugins()
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
            $this->createShipmentStepPostConditionChecker(),
            $this->createGiftCardItemsChecker(),
            CheckoutPageControllerProvider::CHECKOUT_SHIPMENT,
            HomePageControllerProvider::ROUTE_HOME,
            $this->getCheckoutShipmentStepEnterPreCheckPlugins()
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
            $this->getPaymentClient(),
            $this->getPaymentMethodHandler(),
            CheckoutPageControllerProvider::CHECKOUT_PAYMENT,
            HomePageControllerProvider::ROUTE_HOME,
            $this->getFlashMessenger(),
            $this->getCalculationClient(),
            $this->getCheckoutPaymentStepEnterPreCheckPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\SummaryStep
     */
    public function createSummaryStep()
    {
        return new SummaryStep(
            $this->getProductBundleClient(),
            $this->getShipmentService(),
            $this->getConfig(),
            CheckoutPageControllerProvider::CHECKOUT_SUMMARY,
            HomePageControllerProvider::ROUTE_HOME,
            $this->getCheckoutClient()
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
     * @return \SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsCheckerInterface
     */
    public function createGiftCardItemsChecker(): GiftCardItemsCheckerInterface
    {
        return new GiftCardItemsChecker();
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
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface
     */
    public function getPaymentClient(): CheckoutPageToPaymentClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_PAYMENT);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepEnterPreCheckPluginInterface[]
     */
    public function getCheckoutAddressStepEnterPreCheckPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGINS_CHECKOUT_ADDRESS_STEP_ENTER_PRE_CHECK);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepEnterPreCheckPluginInterface[]
     */
    public function getCheckoutShipmentStepEnterPreCheckPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGINS_CHECKOUT_SHIPMENT_STEP_ENTER_PRE_CHECK);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutPaymentStepEnterPreCheckPluginInterface[]
     */
    public function getCheckoutPaymentStepEnterPreCheckPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGINS_CHECKOUT_PAYMENT_STEP_ENTER_PRE_CHECK);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface[]
     */
    public function getCheckoutStepResolverStrategyPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGINS_CHECKOUT_STEP_RESOLVER_STRATEGY);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\StepExecutorInterface
     */
    public function createAddressStepExecutor(): StepExecutorInterface
    {
        return new AddressStepExecutor(
            $this->getCustomerService(),
            $this->getCustomerClient(),
            $this->getShoppingListItemExpanderPlugins()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface
     */
    public function createAddressStepPostConditionChecker(): PostConditionCheckerInterface
    {
        return new AddressStepPostConditionChecker($this->getCustomerService());
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface
     */
    public function createShipmentStepPostConditionChecker(): PostConditionCheckerInterface
    {
        return new ShipmentStepPostConditionChecker($this->getShipmentService(), $this->createGiftCardItemsChecker());
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface
     */
    public function getShipmentService(): CheckoutPageToShipmentServiceInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface
     */
    public function getCustomerService(): CheckoutPageToCustomerServiceInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::SERVICE_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface[]
     */
    public function getShoppingListItemExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_ADDRESS_STEP_EXECUTOR_ADDRESS_TRANSFER_EXPANDERS);
    }
}
