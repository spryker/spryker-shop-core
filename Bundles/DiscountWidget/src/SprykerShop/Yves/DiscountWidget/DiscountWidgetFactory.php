<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientInterface;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToQuoteClientInterface;
use SprykerShop\Yves\DiscountWidget\Form\CartVoucherForm;
use SprykerShop\Yves\DiscountWidget\Form\CheckoutVoucherForm;
use SprykerShop\Yves\DiscountWidget\Handler\VoucherHandler;

class DiscountWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCartVoucherForm()
    {
        return $this->getFormFactory()->create(CartVoucherForm::class);
    }

    /**
     * @return \SprykerShop\Yves\DiscountWidget\Handler\VoucherHandler
     */
    public function createVoucherHandler()
    {
        return new VoucherHandler(
            $this->getCalculationClient(),
            $this->getQuoteClient(),
            $this->getFlashMessenger()
        );
    }

    /**
     * @return \SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientInterface
     */
    public function getCalculationClient(): DiscountWidgetToCalculationClientInterface
    {
        return $this->getProvidedDependency(DiscountWidgetDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): DiscountWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(DiscountWidgetDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected function getFlashMessenger()
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getApplication()
    {
        return $this->getProvidedDependency(DiscountWidgetDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCheckoutVoucherForm()
    {
        return $this->getFormFactory()->create($this->createCheckoutVoucherFormType());
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return string
     */
    protected function createCheckoutVoucherFormType()
    {
        return CheckoutVoucherForm::class;
    }
}
