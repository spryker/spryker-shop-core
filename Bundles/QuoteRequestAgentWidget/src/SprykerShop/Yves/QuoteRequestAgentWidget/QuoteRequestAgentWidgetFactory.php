<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteRequestAgentClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToCartClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToCustomerClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToPersistentCartClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteRequestClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Form\QuoteRequestAgentCartForm;
use SprykerShop\Yves\QuoteRequestAgentWidget\Handler\QuoteRequestAgentCartHandler;
use SprykerShop\Yves\QuoteRequestAgentWidget\Handler\QuoteRequestAgentCartHandlerInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Validator\CompanyUserAutocompleteValidator;
use SprykerShop\Yves\QuoteRequestAgentWidget\Validator\CompanyUserAutocompleteValidatorInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentWidget\QuoteRequestAgentWidgetConfig getConfig()
 */
class QuoteRequestAgentWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestAgentCartForm(): FormInterface
    {
        return $this->getFormFactory()->create(QuoteRequestAgentCartForm::class);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentWidget\Handler\QuoteRequestAgentCartHandlerInterface
     */
    public function createQuoteRequestAgentCartHandler(): QuoteRequestAgentCartHandlerInterface
    {
        return new QuoteRequestAgentCartHandler(
            $this->getCartClient(),
            $this->getQuoteRequestAgentClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentWidget\Validator\CompanyUserAutocompleteValidatorInterface
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
     * @return \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteRequestAgentClientInterface
     */
    public function getQuoteRequestAgentClient(): QuoteRequestAgentWidgetToQuoteRequestAgentClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentWidgetDependencyProvider::CLIENT_QUOTE_REQUEST_AGENT);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): QuoteRequestAgentWidgetToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentWidgetDependencyProvider::CLIENT_QUOTE_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToCartClientInterface
     */
    public function getCartClient(): QuoteRequestAgentWidgetToCartClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentWidgetDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): QuoteRequestAgentWidgetToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentWidgetDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): QuoteRequestAgentWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentWidgetDependencyProvider::CLIENT_CUSTOMER);
    }
}
