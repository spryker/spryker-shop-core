<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToCustomerClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToMessengerClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToPersistentCartClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteRequestAgentClientInterface;
use SprykerShop\Yves\QuoteRequestAgentWidget\Form\QuoteRequestAgentCartForm;
use SprykerShop\Yves\QuoteRequestAgentWidget\Handler\QuoteRequestAgentCartHandler;
use SprykerShop\Yves\QuoteRequestAgentWidget\Handler\QuoteRequestAgentCartHandlerInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
            $this->getQuoteClient(),
            $this->getQuoteRequestAgentClient()
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
     * @return \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteRequestAgentClientInterface
     */
    public function getQuoteRequestAgentClient(): QuoteRequestAgentWidgetToQuoteRequestAgentClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentWidgetDependencyProvider::CLIENT_QUOTE_REQUEST_AGENT);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteRequestAgentWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentWidgetDependencyProvider::CLIENT_QUOTE);
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

    /**
     * @param string $targetUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createRedirectResponse(string $targetUrl): RedirectResponse
    {
        return new RedirectResponse($targetUrl);
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    public function getRouterService(): ChainRouterInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentWidgetDependencyProvider::SERVICE_ROUTER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestAgentWidget\Dependency\Client\QuoteRequestAgentWidgetToMessengerClientInterface
     */
    public function getFlashMessenger(): QuoteRequestAgentWidgetToMessengerClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestAgentWidgetDependencyProvider::CLIENT_MESSENGER);
    }
}
