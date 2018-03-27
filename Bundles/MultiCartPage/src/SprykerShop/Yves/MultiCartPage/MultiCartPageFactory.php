<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MultiCartPage\Form\DataProvider\QuoteFormDataProvider;
use SprykerShop\Yves\MultiCartPage\Form\QuoteForm;
use SprykerShop\Yves\MultiCartPage\Model\CartOperations;

class MultiCartPageFactory extends AbstractFactory
{
    /**
     * @param null|int $idQuote
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteForm(int $idQuote = null)
    {
        return $this->getFormFactory()->create(
            QuoteForm::class,
            $this->createQuoteFormDataProvider()->getData($idQuote)
        );
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Form\DataProvider\QuoteFormDataProviderInterface
     */
    protected function createQuoteFormDataProvider()
    {
        return new QuoteFormDataProvider(
            $this->getMultiCartClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Model\CartOperationsInterface
     */
    public function createCartOperations()
    {
        return new CartOperations(
            $this->getPersistentCartClent(),
            $this->getMultiCartClient(),
            $this->getQuoteClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientInterface
     */
    public function getMultiCartClient()
    {
        return $this->getProvidedDependency(MultiCartPageDependencyProvider::CLIENT_MULTI_CART);
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToQuoteClientInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(MultiCartPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToPersistentCartClientInterface
     */
    public function getPersistentCartClent()
    {
        return $this->getProvidedDependency(MultiCartPageDependencyProvider::CLIENT_PERSISTENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(MultiCartPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }
}
