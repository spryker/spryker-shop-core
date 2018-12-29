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
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Form\QuoteApproveRequestForm;
use SprykerShop\Yves\QuoteApprovalWidget\Form\QuoteApproveRequestFormDataProvider;
use SprykerShop\Yves\QuoteApprovalWidget\Form\QuoteApproveRequestFormDataProviderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetConfig getConfig()
 */
class QuoteApprovalWidgetFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createQuoteApproveRequestForm(QuoteTransfer $quoteTransfer): FormInterface
    {
        $formDataProvider = $this->createQuoteApproveRequestFormDataProvider();

        return $this->getFormFactory()->create(
            QuoteApproveRequestForm::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
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
            $this->getQuoteApprovalClient()
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
}
