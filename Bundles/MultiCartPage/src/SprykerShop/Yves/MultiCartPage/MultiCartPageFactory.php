<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToCartClientInterface;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientInterface;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToQuoteClientInterface;
use SprykerShop\Yves\MultiCartPage\Form\Cloner\FormCloner;
use SprykerShop\Yves\MultiCartPage\Form\DataProvider\QuoteFormDataProvider;
use SprykerShop\Yves\MultiCartPage\Form\DataProvider\QuoteFormDataProviderInterface;
use SprykerShop\Yves\MultiCartPage\Form\MultiCartClearForm;
use SprykerShop\Yves\MultiCartPage\Form\MultiCartDeleteForm;
use SprykerShop\Yves\MultiCartPage\Form\MultiCartDuplicateForm;
use SprykerShop\Yves\MultiCartPage\Form\MultiCartSetDefaultForm;
use SprykerShop\Yves\MultiCartPage\Form\QuoteForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class MultiCartPageFactory extends AbstractFactory
{
    /**
     * @param int|null $idQuote
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteForm(?int $idQuote = null): FormInterface
    {
        return $this->getFormFactory()->create(
            QuoteForm::class,
            $this->createQuoteFormDataProvider()->getData($idQuote),
        );
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Form\DataProvider\QuoteFormDataProviderInterface
     */
    public function createQuoteFormDataProvider(): QuoteFormDataProviderInterface
    {
        return new QuoteFormDataProvider(
            $this->getMultiCartClient(),
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
     * @return \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToCartClientInterface
     */
    public function getCartClient(): MultiCartPageToCartClientInterface
    {
        return $this->getProvidedDependency(MultiCartPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToQuoteClientInterface
     */
    public function getQuoteClient(): MultiCartPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(MultiCartPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Form\Cloner\FormCloner
     */
    public function getFormCloner(): FormCloner
    {
        return new FormCloner();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMultiCartDuplicateForm(): FormInterface
    {
        return $this->getFormFactory()->create(MultiCartDuplicateForm::class);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMultiCartDeleteForm(QuoteTransfer $data, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(MultiCartDeleteForm::class, $data, $options);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMultiCartClearForm(): FormInterface
    {
        return $this->getFormFactory()->create(MultiCartClearForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMultiCartSetDefaultForm(): FormInterface
    {
        return $this->getFormFactory()->create(MultiCartSetDefaultForm::class);
    }
}
