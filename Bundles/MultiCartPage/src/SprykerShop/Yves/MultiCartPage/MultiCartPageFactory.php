<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientInterface;
use SprykerShop\Yves\MultiCartPage\Form\DataProvider\QuoteFormDataProvider;
use SprykerShop\Yves\MultiCartPage\Form\DataProvider\QuoteFormDataProviderInterface;
use SprykerShop\Yves\MultiCartPage\Form\QuoteForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class MultiCartPageFactory extends AbstractFactory
{
    /**
     * @param null|int $idQuote
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteForm(?int $idQuote = null): FormInterface
    {
        return $this->getFormFactory()->create(
            QuoteForm::class,
            $this->createQuoteFormDataProvider()->getData($idQuote)
        );
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Form\DataProvider\QuoteFormDataProviderInterface
     */
    public function createQuoteFormDataProvider(): QuoteFormDataProviderInterface
    {
        return new QuoteFormDataProvider(
            $this->getMultiCartClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientInterface
     */
    public function getMultiCartClient(): MultiCartPageToMultiCartClientInterface
    {
        return $this->getProvidedDependency(MultiCartPageDependencyProvider::CLIENT_MULTI_CART);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Plugin\CartDeleteCompanyUsersListWidgetPluginInterface[]
     */
    public function getCartDeleteCompanyUsersListWidgetPlugins(): array
    {
        return $this->getProvidedDependency(MultiCartPageDependencyProvider::PLUGINS_CART_DELETE_COMPANY_USERS_LIST_WIDGET);
    }
}
