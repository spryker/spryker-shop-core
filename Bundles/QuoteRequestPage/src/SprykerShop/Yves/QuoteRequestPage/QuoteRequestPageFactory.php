<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use SprykerShop\Yves\QuoteRequestPage\CheckoutStep\EntryStep;
use SprykerShop\Yves\QuoteRequestPage\CheckoutStep\SaveRequestForQuoteStep;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCustomerClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToPersistentCartClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToUtilDateTimeServiceInterface;
use SprykerShop\Yves\QuoteRequestPage\Form\DataProvider\QuoteRequestFormDataProvider;
use SprykerShop\Yves\QuoteRequestPage\Form\Handler\QuoteRequestHandler;
use SprykerShop\Yves\QuoteRequestPage\Form\Handler\QuoteRequestHandlerInterface;
use SprykerShop\Yves\QuoteRequestPage\Form\QuoteRequestEditAddressConfirmForm;
use SprykerShop\Yves\QuoteRequestPage\Form\QuoteRequestEditItemsConfirmForm;
use SprykerShop\Yves\QuoteRequestPage\Form\QuoteRequestEditShipmentConfirmForm;
use SprykerShop\Yves\QuoteRequestPage\Form\QuoteRequestForm;
use SprykerShop\Yves\QuoteRequestPage\Grouper\ShipmentGrouper;
use SprykerShop\Yves\QuoteRequestPage\Grouper\ShipmentGrouperInterface;
use SprykerShop\Yves\QuoteRequestPage\Resolver\CheckoutStepResolver;
use SprykerShop\Yves\QuoteRequestPage\Resolver\CheckoutStepResolverInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig getConfig()
 */
class QuoteRequestPageFactory extends AbstractFactory
{
    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::CHECKOUT_INDEX
     */
    protected const ROUTE_CHECKOUT_INDEX = 'checkout-index';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_QUOTE_REQUEST
     */
    protected const ROUTE_QUOTE_REQUEST = 'quote-request';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_QUOTE_REQUEST_SAVE
     */
    protected const ROUTE_QUOTE_REQUEST_SAVE = 'quote-request/save';

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer|null $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestForm(?QuoteRequestTransfer $quoteRequestTransfer = null): FormInterface
    {
        $quoteRequestFormDataProvider = $this->createQuoteRequestFormDataProvider();

        return $this->getFormFactory()->create(
            QuoteRequestForm::class,
            $quoteRequestFormDataProvider->getData($quoteRequestTransfer)
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Form\DataProvider\QuoteRequestFormDataProvider
     */
    public function createQuoteRequestFormDataProvider(): QuoteRequestFormDataProvider
    {
        return new QuoteRequestFormDataProvider(
            $this->getCompanyUserClient(),
            $this->getCartClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Form\Handler\QuoteRequestHandlerInterface
     */
    public function createQuoteRequestHandler(): QuoteRequestHandlerInterface
    {
        return new QuoteRequestHandler(
            $this->getQuoteRequestClient(),
            $this->getPersistentCartClient(),
            $this->getQuoteClient(),
            $this->getCustomerClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Grouper\ShipmentGrouperInterface
     */
    public function createShipmentGrouper(): ShipmentGrouperInterface
    {
        return new ShipmentGrouper(
            $this->getShipmentService()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestEditItemsConfirmForm(QuoteRequestTransfer $quoteRequestTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            QuoteRequestEditItemsConfirmForm::class,
            $quoteRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestEditAddressConfirmForm(QuoteRequestTransfer $quoteRequestTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            QuoteRequestEditAddressConfirmForm::class,
            $quoteRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestEditShipmentConfirmForm(QuoteRequestTransfer $quoteRequestTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            QuoteRequestEditShipmentConfirmForm::class,
            $quoteRequestTransfer
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function createEntryStep(): StepInterface
    {
        return new EntryStep(
            static::ROUTE_CHECKOUT_INDEX,
            static::ROUTE_QUOTE_REQUEST
        );
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function createSaveRequestForQuoteStep(): StepInterface
    {
        return new SaveRequestForQuoteStep(
            static::ROUTE_QUOTE_REQUEST_SAVE,
            static::ROUTE_QUOTE_REQUEST
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Resolver\CheckoutStepResolverInterface
     */
    public function createCheckoutStepResolver(): CheckoutStepResolverInterface
    {
        return new CheckoutStepResolver(
            $this->createEntryStep(),
            $this->createSaveRequestForQuoteStep()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): QuoteRequestPageToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Service\QuoteRequestPageToShipmentServiceInterface
     */
    public function getShipmentService(): QuoteRequestPageToShipmentServiceInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): QuoteRequestPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): QuoteRequestPageToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_QUOTE_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientInterface
     */
    public function getCartClient(): QuoteRequestPageToCartClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): QuoteRequestPageToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCustomerClientInterface
     */
    public function getCustomerClient(): QuoteRequestPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteRequestPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPageExtension\Dependency\Plugin\QuoteRequestFormMetadataFieldPluginInterface[]
     */
    public function getQuoteRequestFormMetadataFieldPlugins(): array
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::PLUGINS_QUOTE_REQUEST_FORM_METADATA_FIELD);
    }
}
