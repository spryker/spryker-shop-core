<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuickOrderPage\Form\DataProvider\QuckOrderFormDataProvider;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageDependencyProvider;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @param mixed $data
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getQuickOrderForm($data = null, array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(QuickOrderForm::class, $data, $formOptions);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\DataProvider\QuckOrderFormDataProvider
     */
    public function createQuickOrderDataProvider(): QuckOrderFormDataProvider
    {
        //$this->getCustomerClient(), $this->getStore()
        return new QuckOrderFormDataProvider();
    }

    /**
     * @return \Spryker\Client\Customer\CustomerClientInterface
     */
    protected function getCustomerClient()
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::STORE);
    }
}
