<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentQuoteRequestWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToAgentQuoteRequestClientInterface;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCartClientInterface;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCustomerClientInterface;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToPersistentCartClientInterface;
use SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToQuoteRequestClientInterface;
use SprykerShop\Yves\AgentQuoteRequestWidget\Form\AgentQuoteRequestCartForm;
use SprykerShop\Yves\AgentQuoteRequestWidget\Handler\AgentQuoteRequestCartHandler;
use SprykerShop\Yves\AgentQuoteRequestWidget\Handler\AgentQuoteRequestCartHandlerInterface;
use SprykerShop\Yves\AgentQuoteRequestWidget\Validator\CompanyUserAutocompleteValidator;
use SprykerShop\Yves\AgentQuoteRequestWidget\Validator\CompanyUserAutocompleteValidatorInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method \SprykerShop\Yves\AgentQuoteRequestWidget\AgentQuoteRequestWidgetConfig getConfig()
 */
class AgentQuoteRequestWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAgentQuoteRequestCartForm(): FormInterface
    {
        return $this->getFormFactory()->create(AgentQuoteRequestCartForm::class);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestWidget\Handler\AgentQuoteRequestCartHandlerInterface
     */
    public function createAgentQuoteRequestCartHandler(): AgentQuoteRequestCartHandlerInterface
    {
        return new AgentQuoteRequestCartHandler(
            $this->getCartClient(),
            $this->getAgentQuoteRequestClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestWidget\Validator\CompanyUserAutocompleteValidatorInterface
     */
    public function createCompanyUserAutocompleteValidator(): CompanyUserAutocompleteValidatorInterface
    {
        return new CompanyUserAutocompleteValidator(
            $this->getValidator()
        );
    }

    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToAgentQuoteRequestClientInterface
     */
    public function getAgentQuoteRequestClient(): AgentQuoteRequestWidgetToAgentQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestWidgetDependencyProvider::CLIENT_AGENT_QUOTE_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): AgentQuoteRequestWidgetToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestWidgetDependencyProvider::CLIENT_QUOTE_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCartClientInterface
     */
    public function getCartClient(): AgentQuoteRequestWidgetToCartClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestWidgetDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): AgentQuoteRequestWidgetToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestWidgetDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestWidget\Dependency\Client\AgentQuoteRequestWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): AgentQuoteRequestWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(AgentQuoteRequestWidgetDependencyProvider::CLIENT_CUSTOMER);
    }
}
