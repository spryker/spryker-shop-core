<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToGlossaryStorageClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToMoneyClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Form\QuoteApproveRequestForm;
use SprykerShop\Yves\QuoteApprovalWidget\Form\QuoteApproveRequestFormDataProvider;
use SprykerShop\Yves\QuoteApprovalWidget\Form\QuoteApproveRequestFormDataProviderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class QuoteApprovalWidgetFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createQuoteApproveRequestForm(QuoteTransfer $quoteTransfer, string $localeName): FormInterface
    {
        $formDataProvider = $this->createQuoteApproveRequestFormDataProvider();

        return $this->getFormFactory()->create(
            QuoteApproveRequestForm::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer, $localeName)
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
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Form\QuoteApproveRequestFormDataProviderInterface
     */
    public function createQuoteApproveRequestFormDataProvider(): QuoteApproveRequestFormDataProviderInterface
    {
        return new QuoteApproveRequestFormDataProvider(
            $this->getQuoteApprovalClient(),
            $this->getCustomerClient(),
            $this->getGlossaryStorageClient(),
            $this->getMoneyClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface
     */
    public function getQuoteApprovalClient(): QuoteApprovalWidgetToQuoteApprovalClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalWidgetDependencyProvider::CLIENT_QUOTE_APPROVAL);
    }

    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteApprovalWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalWidgetDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): QuoteApprovalWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToMoneyClientInterface
     */
    public function getMoneyClient(): QuoteApprovalWidgetToMoneyClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalWidgetDependencyProvider::CLIENT_MONEY);
    }

    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): QuoteApprovalWidgetToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalWidgetDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
