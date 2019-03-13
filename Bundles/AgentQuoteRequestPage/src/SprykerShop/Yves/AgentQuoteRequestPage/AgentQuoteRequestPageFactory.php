<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestPage;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToAgentQuoteRequestClientInterface;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToCompanyUserClientInterface;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteClientInterface;
use SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface;
use SprykerShop\Yves\AgentQuoteRequestPage\Form\AgentQuoteRequestCreateForm;
use SprykerShop\Yves\AgentQuoteRequestPage\Form\AgentQuoteRequestEditItemsConfirmForm;
use SprykerShop\Yves\AgentQuoteRequestPage\Form\AgentQuoteRequestForm;
use SprykerShop\Yves\AgentQuoteRequestPage\Form\DataProvider\AgentQuoteRequestFormDataProvider;
use SprykerShop\Yves\QuoteRequestPage\Form\Handler\AgentQuoteRequestCreateHandler;
use SprykerShop\Yves\QuoteRequestPage\Form\Handler\AgentQuoteRequestCreateHandlerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageConfig getConfig()
 */
class AgentQuoteRequestPageFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAgentQuoteRequestForm(QuoteRequestTransfer $quoteRequestTransfer): FormInterface
    {
        $agentQuoteRequestFormDataProvider = $this->createAgentQuoteRequestFormDataProvider();

        return $this->getFormFactory()->create(
            AgentQuoteRequestForm::class,
            $agentQuoteRequestFormDataProvider->getData($quoteRequestTransfer)
        );
    }

    /**
     * @param string $quoteRequestReference
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAgentQuoteRequestEditItemsConfirmForm(string $quoteRequestReference): FormInterface
    {
        $agentQuoteRequestFormDataProvider = $this->createAgentQuoteRequestFormDataProvider();

        return $this->getFormFactory()->create(
            AgentQuoteRequestEditItemsConfirmForm::class,
            $agentQuoteRequestFormDataProvider->getData($quoteRequestReference)
        );
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAgentQuoteRequestCreateForm(): FormInterface
    {
        return $this->getFormFactory()->create(AgentQuoteRequestCreateForm::class);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestPage\Form\DataProvider\AgentQuoteRequestFormDataProvider
     */
    public function createAgentQuoteRequestFormDataProvider(): AgentQuoteRequestFormDataProvider
    {
        return new AgentQuoteRequestFormDataProvider(
            $this->getQuoteRequestClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Form\Handler\AgentQuoteRequestCreateHandlerInterface
     */
    public function createAgentQuoteRequestCreateHandler(): AgentQuoteRequestCreateHandlerInterface
    {
        return new AgentQuoteRequestCreateHandler(
            $this->getAgentQuoteRequestClient()
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
     * @return \SprykerShop\Yves\AgentQuoteRequestPageExtension\Dependency\Plugin\AgentQuoteRequestFormMetadataFieldPluginInterface[]
     */
    public function getAgentQuoteRequestFormMetadataFieldPlugins(): array
    {
        return $this->getProvidedDependency(AgentQuoteRequestPageDependencyProvider::PLUGINS_AGENT_QUOTE_REQUEST_FORM_METADATA_FIELD);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): AgentQuoteRequestPageToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestPageDependencyProvider::CLIENT_QUOTE_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): AgentQuoteRequestPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToQuoteClientInterface
     */
    public function getQuoteClient(): AgentQuoteRequestPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestPage\Dependency\Client\AgentQuoteRequestPageToAgentQuoteRequestClientInterface
     */
    public function getAgentQuoteRequestClient(): AgentQuoteRequestPageToAgentQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestPageDependencyProvider::CLIENT_AGENT_QUOTE_REQUEST);
    }
}
