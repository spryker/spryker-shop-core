<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CheckoutPage\Form\FormFactory;
use SprykerShop\Yves\CheckoutPage\Process\StepFactory;
use SprykerShop\Yves\DiscountWidget\Handler\VoucherHandler;

class CheckoutPageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\StepEngine\Process\StepEngineInterface
     */
    public function createCheckoutProcess()
    {
        return $this->createStepFactory()->createStepEngine(
            $this->createStepFactory()->createStepCollection()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\StepFactory
     */
    public function createStepFactory()
    {
        return new StepFactory();
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Form\FormFactory
     */
    public function createCheckoutFormFactory()
    {
        return new FormFactory();
    }

    /**
     * @return \SprykerShop\Yves\DiscountWidget\Handler\VoucherHandler
     */
    public function createVoucherHandler()
    {
        return new VoucherHandler(
            $this->getCalculationClient(),
            $this->getCartClient(),
            $this->getFlashMessenger()
        );
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected function getUrlGenerator()
    {
        return $this->getApplication()['url_generator'];
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getApplication()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Spryker\Client\Calculation\CalculationClientInterface
     */
    protected function getCalculationClient()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    protected function getCartClient()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected function getFlashMessenger()
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Plugin\CheckoutBreadcrumbPlugin
     */
    public function getCheckoutBreadcrumbPlugin()
    {
        return $this->getProvidedDependency(CheckoutPageDependencyProvider::PLUGIN_CHECKOUT_BREADCRUMB);
    }
}
