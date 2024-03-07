<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentPage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerShop\Yves\CheckoutPage\Form\StepEngine\ExtraOptionsSubFormInterface;
use SprykerShop\Yves\PaymentPage\Dependency\Client\PaymentPageToCartClientInterface;
use SprykerShop\Yves\PaymentPage\Dependency\Client\PaymentPageToCustomerClientInterface;
use SprykerShop\Yves\PaymentPage\Dependency\Client\PaymentPageToSalesClientInterface;
use SprykerShop\Yves\PaymentPage\Form\DataProvider\PaymentForeignFormDataProvider;
use SprykerShop\Yves\PaymentPage\Form\PaymentForeignSubForm;
use SprykerShop\Yves\PaymentPage\Plugin\StepEngine\AbstractPaymentForeignSubFormPlugin;
use SprykerShop\Yves\PaymentPage\Plugin\StepEngine\PaymentForeignSubFormPlugin;

/**
 * @method \SprykerShop\Yves\PaymentPage\PaymentPageConfig getConfig()
 */
class PaymentPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\PaymentPage\Plugin\StepEngine\AbstractPaymentForeignSubFormPlugin
     */
    public function createPaymentForeignSubFormPlugin(): AbstractPaymentForeignSubFormPlugin
    {
        return new PaymentForeignSubFormPlugin();
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Form\StepEngine\ExtraOptionsSubFormInterface
     */
    public function createPaymentForeignSubForm(): ExtraOptionsSubFormInterface
    {
        return new PaymentForeignSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createPaymentForeignFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new PaymentForeignFormDataProvider();
    }

    /**
     * @return \SprykerShop\Yves\PaymentPage\Dependency\Client\PaymentPageToCartClientInterface
     */
    public function getCartClient(): PaymentPageToCartClientInterface
    {
        return $this->getProvidedDependency(PaymentPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\PaymentPage\Dependency\Client\PaymentPageToCustomerClientInterface
     */
    public function getCustomerClient(): PaymentPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(PaymentPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\PaymentPage\Dependency\Client\PaymentPageToSalesClientInterface
     */
    public function getSalesClient(): PaymentPageToSalesClientInterface
    {
        return $this->getProvidedDependency(PaymentPageDependencyProvider::CLIENT_SALES);
    }
}
