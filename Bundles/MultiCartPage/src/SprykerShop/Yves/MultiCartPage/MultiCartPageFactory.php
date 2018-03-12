<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MultiCartPage\Form\QuoteForm;

class MultiCartPageFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuoteForm()
    {
        return $this->getFormFactory()->create(QuoteForm::class);
    }

    /**
     * @return \SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientInterface
     */
    public function getMultiCartClient()
    {
        return $this->getProvidedDependency(MultiCartPageDependencyProvider::CLIENT_MULTI_CART);
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
