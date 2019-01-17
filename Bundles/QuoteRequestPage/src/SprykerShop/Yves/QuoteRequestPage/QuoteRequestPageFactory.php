<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Form\DataProvider\QuoteRequestFormDataProvider;
use SprykerShop\Yves\QuoteRequestPage\Form\QuoteRequestForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig getConfig()
 */
class QuoteRequestPageFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestForm(QuoteRequestTransfer $quoteRequestTransfer): FormInterface
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
            $this->getQuoteClient(),
            $this->getConfig()
        );
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
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteRequestPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPageExtension\Dependency\Plugin\QuoteRequestFormMetadataFieldExpanderPluginInterface[]
     */
    public function getQuoteRequestFormMetadataFieldExpanderPlugins(): array
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::PLUGIN_QUOTE_REQUEST_FORM_METADATA_FIELD_EXPANDERS);
    }
}
