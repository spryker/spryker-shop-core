<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCustomerClientInterface;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface;
use Symfony\Component\Form\FormFactory;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageConfig getConfig()
 */
class QuoteRequestPageFactory extends AbstractFactory
{
//    /**
//     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $data
//     * @param array $options
//     *
//     * @return \Symfony\Component\Form\FormInterface
//     */
//    public function getQuoteRequestUpdateForm(QuoteRequestTransfer $data = null, array $options = []): FormInterface
//    {
//        return $this->getFormFactory()->create(QuoteRequestUpdateForm::class, $data, $options);
//    }
//
//    /**
//     * @return \SprykerShop\Yves\QuoteRequestPage\Form\DataProvider\QuoteRequestUpdateFormDataProvider
//     */
//    public function createQuoteRequestUpdateFormUDataProvider(): QuoteRequestUpdateFormDataProvider
//    {
//        return new QuoteRequestUpdateFormDataProvider(
//            $this->getQuoteRequestClient(),
//            $this->getCustomerClient()
//        );
//    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCustomerClientInterface
     */
    public function getCustomerClient(): QuoteRequestPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): QuoteRequestPageToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestPageDependencyProvider::CLIENT_QUOTE_REQUEST);
    }
}
