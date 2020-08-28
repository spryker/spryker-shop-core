<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCompanyUserClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCustomerClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToPersistentCartClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Form\QuoteRequestCartForm;
use SprykerShop\Yves\QuoteRequestWidget\Handler\QuoteRequestCartHandler;
use SprykerShop\Yves\QuoteRequestWidget\Handler\QuoteRequestCartHandlerInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetConfig getConfig()
 */
class QuoteRequestWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\QuoteRequestWidget\Handler\QuoteRequestCartHandlerInterface
     */
    public function createQuoteRequestCartHandler(): QuoteRequestCartHandlerInterface
    {
        return new QuoteRequestCartHandler(
            $this->getQuoteClient(),
            $this->getQuoteRequestClient(),
            $this->getCompanyUserClient()
        );
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
     * @return \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): QuoteRequestWidgetToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestWidgetDependencyProvider::CLIENT_QUOTE_REQUEST);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteRequestCartForm(): FormInterface
    {
        return $this->getFormFactory()->create(QuoteRequestCartForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToPersistentCartClientInterface
     */
    public function getPersistentCartClient(): QuoteRequestWidgetToPersistentCartClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestWidgetDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteRequestWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestWidgetDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): QuoteRequestWidgetToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestWidgetDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): QuoteRequestWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    public function getRouterService(): ChainRouterInterface
    {
        return $this->getProvidedDependency(QuoteRequestWidgetDependencyProvider::SERVICE_ROUTER);
    }
}
