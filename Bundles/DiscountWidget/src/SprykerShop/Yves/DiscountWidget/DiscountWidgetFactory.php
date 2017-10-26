<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget;

use Pyz\Yves\Discount\Handler\VoucherHandler;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\DiscountWidget\Form\VoucherForm;

class DiscountWidgetFactory extends AbstractFactory
{

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getVoucherForm()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY)
            ->create($this->createVoucherFormType());
    }

    /**
     * @return \Symfony\Component\Form\AbstractType
     */
    protected function createVoucherFormType()
    {
        return new VoucherForm();
    }

    /**
     * @return \Pyz\Yves\Discount\Handler\VoucherHandlerInterface
     */
    public function createCartVoucherHandler()
    {
        return new VoucherHandler(
            $this->getCalculationClient(),
            $this->getCartClient(),
            $this->getFlashMessenger()
        );
    }

    /**
     * @return \Spryker\Client\Calculation\CalculationClientInterface
     */
    public function getCalculationClient()
    {
        return $this->getProvidedDependency(DiscountWidgetDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \Spryker\Client\Cart\CartClient
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(DiscountWidgetDependencyProvider::CLIENT_CART);
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

}
