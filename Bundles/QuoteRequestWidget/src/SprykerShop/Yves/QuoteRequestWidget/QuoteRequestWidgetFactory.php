<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCartClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToPersistentCartClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Form\QuoteRequestCartForm;
use SprykerShop\Yves\QuoteRequestWidget\Handler\QuoteRequestCartHandler;
use SprykerShop\Yves\QuoteRequestWidget\Handler\QuoteRequestCartHandlerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

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
            $this->getCartClient(),
            $this->getQuoteRequestClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCartClientInterface
     */
    public function getCartClient(): QuoteRequestWidgetToCartClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestWidgetDependencyProvider::CLIENT_CART);
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
}
