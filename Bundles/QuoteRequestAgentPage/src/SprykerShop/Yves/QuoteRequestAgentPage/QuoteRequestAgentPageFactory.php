<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteRequestAgentPage\Converter\QuoteRequestConverter;
use SprykerShop\Yves\QuoteRequestAgentPage\Converter\QuoteRequestConverterInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCartClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCompanyUserClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCustomerClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToPriceClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToStoreClientInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToUtilDateTimeServiceInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\DataProvider\QuoteRequestAgentFormDataProvider;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\Handler\QuoteRequestAgentCreateHandler;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\Handler\QuoteRequestAgentCreateHandlerInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\Listener\QuoteRequestAgentFormEventsListener;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\Listener\QuoteRequestAgentFormEventsListenerInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\QuoteRequestAgentCreateForm;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\QuoteRequestAgentEditAddressConfirmForm;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\QuoteRequestAgentEditItemsConfirmForm;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\QuoteRequestAgentEditShipmentConfirmForm;
use SprykerShop\Yves\QuoteRequestAgentPage\Form\QuoteRequestAgentForm;
use SprykerShop\Yves\QuoteRequestAgentPage\Grouper\ShipmentGrouper;
use SprykerShop\Yves\QuoteRequestAgentPage\Grouper\ShipmentGrouperInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Impersonator\CompanyUserImpersonator;
use SprykerShop\Yves\QuoteRequestAgentPage\Impersonator\CompanyUserImpersonatorInterface;
use SprykerShop\Yves\QuoteRequestAgentPage\Validator\ShipmentValidator;
use SprykerShop\Yves\QuoteRequestAgentPage\Validator\ShipmentValidatorInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageConfig getConfig()
 */
class QuoteRequestAgentPageFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestAgentForm(QuoteRequestTransfer $quoteRequestTransfer): FormInterface
    {
        $quoteRequestAgentFormDataProvider = $this->createQuoteRequestAgentFormDataProvider();

        return $this->getFormFactory()->create(
            QuoteRequestAgentForm::class,
            $quoteRequestTransfer,
            $quoteRequestAgentFormDataProvider->getOptions($quoteRequestTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestAgentEditItemsConfirmForm(QuoteRequestTransfer $quoteRequestTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            QuoteRequestAgentEditItemsConfirmForm::class,
            $quoteRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestAgentEditAddressConfirmForm(QuoteRequestTransfer $quoteRequestTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            QuoteRequestAgentEditAddressConfirmForm::class,
            $quoteRequestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestAgentEditShipmentConfirmForm(QuoteRequestTransfer $quoteRequestTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            QuoteRequestAgentEditShipmentConfirmForm::class,
            $quoteRequestTransfer
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Form\DataProvider\QuoteRequestAgentFormDataProvider
     */
    public function createQuoteRequestAgentFormDataProvider(): QuoteRequestAgentFormDataProvider
    {
        return new QuoteRequestAgentFormDataProvider(
            $this->getCartClient(),
            $this->getPriceClient(),
            $this->createShipmentGrouper()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestAgentCreateForm(): FormInterface
    {
        return $this->getFormFactory()->create(QuoteRequestAgentCreateForm::class);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Form\Handler\QuoteRequestAgentCreateHandlerInterface
     */
    public function createQuoteRequestAgentCreateHandler(): QuoteRequestAgentCreateHandlerInterface
    {
        return new QuoteRequestAgentCreateHandler(
            $this->getQuoteRequestAgentClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Grouper\ShipmentGrouperInterface
     */
    public function createShipmentGrouper(): ShipmentGrouperInterface
    {
        return new ShipmentGrouper(
            $this->getShipmentService()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Impersonator\CompanyUserImpersonatorInterface
     */
    public function createCompanyUserImpersonator(): CompanyUserImpersonatorInterface
    {
        return new CompanyUserImpersonator();
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Converter\QuoteRequestConverterInterface
     */
    public function createQuoteRequestConverter(): QuoteRequestConverterInterface
    {
        return new QuoteRequestConverter(
            $this->getMessengerClient(),
            $this->getQuoteRequestAgentClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Validator\ShipmentValidatorInterface
     */
    public function createShipmentValidator(): ShipmentValidatorInterface
    {
        return new ShipmentValidator($this->getQuoteRequestClient());
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Form\Listener\QuoteRequestAgentFormEventsListenerInterface
     */
    public function createQuoteRequestAgentFormEventsListener(): QuoteRequestAgentFormEventsListenerInterface
    {
        return new QuoteRequestAgentFormEventsListener();
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPageExtension\Dependency\Plugin\QuoteRequestAgentFormMetadataFieldPluginInterface[]
     */
    public function getQuoteRequestAgentFormMetadataFieldPlugins(): array
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::PLUGINS_QUOTE_REQUEST_AGENT_FORM_METADATA_FIELD);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): QuoteRequestAgentPageToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::CLIENT_QUOTE_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToShipmentServiceInterface
     */
    public function getShipmentService(): QuoteRequestAgentPageToShipmentServiceInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): QuoteRequestAgentPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteRequestAgentPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCartClientInterface
     */
    public function getCartClient(): QuoteRequestAgentPageToCartClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToPriceClientInterface
     */
    public function getPriceClient(): QuoteRequestAgentPageToPriceClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Service\QuoteRequestAgentPageToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): QuoteRequestAgentPageToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToQuoteRequestAgentClientInterface
     */
    public function getQuoteRequestAgentClient(): QuoteRequestAgentPageToQuoteRequestAgentClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::CLIENT_QUOTE_REQUEST_AGENT);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToCustomerClientInterface
     */
    public function getCustomerClient(): QuoteRequestAgentPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToStoreClientInterface
     */
    public function getStoreClient(): QuoteRequestAgentPageToStoreClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentPage\Dependency\Client\QuoteRequestAgentPageToMessengerClientInterface
     */
    public function getMessengerClient(): QuoteRequestAgentPageToMessengerClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentPageDependencyProvider::CLIENT_MESSENGER);
    }
}
