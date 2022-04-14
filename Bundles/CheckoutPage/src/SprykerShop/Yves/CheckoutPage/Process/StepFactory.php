<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Process;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
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
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToQuoteClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CheckoutPage\Extractor\PaymentMethodKeyExtractor;
use SprykerShop\Yves\CheckoutPage\Extractor\PaymentMethodKeyExtractorInterface;
use SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsChecker;
use SprykerShop\Yves\CheckoutPage\GiftCard\GiftCardItemsCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\AddressStepExecutor;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AddressStep\PostConditionChecker as AddressStepPostConditionChecker;
use SprykerShop\Yves\CheckoutPage\Process\Steps\CustomerStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\EntryStep;
use SprykerShop\Yves\CheckoutPage\Process\Steps\ErrorStep;
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

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class StepFactory extends AbstractFactory
{
    /**
     * @var int
     */
    protected const ERROR_CODE_GENERAL_FAILURE = 399;

    /**
     * @var string
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_LOGOUT
     *
     * @var string
     */
    protected const ROUTE_LOGOUT = 'logout';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_CUSTOMER} instead.
     *
     * @var string
     */
    protected const CHECKOUT_CUSTOMER = 'checkout-customer';

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
     * @return \SprykerShop\Yves\CheckoutPage\Extractor\PaymentMethodKeyExtractorInterface
     */
    public function createPaymentMethodKeyExtractor(): PaymentMethodKeyExtractorInterface
    {
        return new PaymentMethodKeyExtractor();
    }

    /**
     * @param \Spryker\Yves\StepEngine\Process\StepCollectionInterface $stepCollection
     *
     * @return \Spryker\Yves\StepEngine\Process\StepEngine
     */
    public function createStepEngine(StepCollectionInterface $stepCollection)
    {
        return new StepEngine(
            $stepCollection,
            $this->createDataContainer(),
            $this->createStepBreadcrumbGenerator(),
            $this->getCheckoutPageStepEnginePreRenderPlugins(),
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepCollectionInterface
     */
    public function createStepCollection()
    {
        return new StepCollection(
            $this->getRouter(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_ERROR,
        );
    }

    /**
     * @return array<\Spryker\Yves\StepEngine\Dependency\Step\StepInterface>
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
            $this->createErrorStep(),
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
            $this->createStepCollection(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\EntryStep
     */
    public function createEntryStep()
    {
        return new EntryStep(
            static::ROUTE_CART,
            $this->getConfig()->getEscapeRoute(),
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
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_CUSTOMER,
            $this->getConfig()->getEscapeRoute(),
            $this->getRouter()->generate(static::ROUTE_LOGOUT),
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
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_ADDRESS,
            $this->getConfig()->getEscapeRoute(),
            $this->getCheckoutAddressStepEnterPreCheckPlugins(),
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
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SHIPMENT,
            $this->getConfig()->getEscapeRoute(),
            $this->getCheckoutShipmentStepEnterPreCheckPlugins(),
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
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PAYMENT,
            $this->getConfig()->getEscapeRoute(),
            $this->getFlashMessenger(),
            $this->getCalculationClient(),
            $this->getCheckoutPaymentStepEnterPreCheckPlugins(),
            $this->createPaymentMethodKeyExtractor(),
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
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUMMARY,
            $this->getConfig()->getEscapeRoute(),
            $this->getCheckoutClient(),
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
            $this->getLocaleClient()->getCurrentLocale(),
            $this->getGlossaryStorageClient(),
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PLACE_ORDER,
            $this->getConfig()->getEscapeRoute(),
            [
                static::ERROR_CODE_GENERAL_FAILURE => static::ROUTE_CART,
                'payment failed' => CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_PAYMENT,
                'shipment failed' => CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SHIPMENT,
            ],
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
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUCCESS,
            $this->getConfig()->getEscapeRoute(),
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function createErrorStep(): StepInterface
    {
        return new ErrorStep(
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_ERROR,
            $this->getConfig()->getEscapeRoute(),
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
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::SERVICE_FLASH_MESSENGER);
    }

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CheckoutPage\Process\StepFactory::getRouter()} instead.
     *
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    public function getUrlGenerator()
    {
        return $this->getApplication()['url_generator'];
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    public function getRouter()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::SERVICE_ROUTER);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
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
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutAddressStepEnterPreCheckPluginInterface>
     */
    public function getCheckoutAddressStepEnterPreCheckPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGINS_CHECKOUT_ADDRESS_STEP_ENTER_PRE_CHECK);
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutShipmentStepEnterPreCheckPluginInterface>
     */
    public function getCheckoutShipmentStepEnterPreCheckPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGINS_CHECKOUT_SHIPMENT_STEP_ENTER_PRE_CHECK);
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutPaymentStepEnterPreCheckPluginInterface>
     */
    public function getCheckoutPaymentStepEnterPreCheckPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGINS_CHECKOUT_PAYMENT_STEP_ENTER_PRE_CHECK);
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\CheckoutStepResolverStrategyPluginInterface>
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
            $this->getShoppingListItemExpanderPlugins(),
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
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\AddressTransferExpanderPluginInterface>
     */
    public function getShoppingListItemExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_ADDRESS_STEP_EXECUTOR_ADDRESS_TRANSFER_EXPANDERS);
    }

    /**
     * @return array<\SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\StepEngine\CheckoutPageStepEnginePreRenderPluginInterface>
     */
    public function getCheckoutPageStepEnginePreRenderPlugins(): array
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGINS_CHECKOUT_PAGE_STEP_ENGINE_PRE_RENDER);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface
     */
    public function getLocaleClient(): CheckoutPageToLocaleClientInterface
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_LOCALE);
    }
}
