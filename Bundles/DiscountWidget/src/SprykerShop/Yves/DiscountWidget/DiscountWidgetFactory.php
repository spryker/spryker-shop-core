<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget;

use Spryker\Client\Calculation\CalculationClient;
use Spryker\Client\Cart\CartClient;
use Spryker\Client\Messenger\MessengerClient;
use Spryker\Client\Quote\QuoteClient;
use Spryker\Client\ZedRequest\ZedRequestClient;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCalculationClientBridge;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientBridge;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToMessengerClientBridge;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientBridge;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToZedRequestClientBridge;
use SprykerShop\Yves\CartPage\Handler\CodeHandler;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientInterface;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToQuoteClientInterface;
use SprykerShop\Yves\DiscountWidget\Form\CartVoucherForm;
use SprykerShop\Yves\DiscountWidget\Form\CheckoutVoucherForm;
use SprykerShop\Yves\DiscountWidget\Plugin\GiftCardCodeHandler;
use SprykerShop\Yves\DiscountWidget\Plugin\VoucherCodeHandler;

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
     * @return CodeHandler
     */
    public function createVoucherHandler()
    {
        return new CodeHandler(
            new CartPageToCartClientBridge(new CartClient()),
            new CartPageToCalculationClientBridge(new CalculationClient()),
            new CartPageToQuoteClientBridge(new QuoteClient()),
            new CartPageToZedRequestClientBridge(new ZedRequestClient()),
            new CartPageToMessengerClientBridge(new MessengerClient()),
            [
                new VoucherCodeHandler(),
                new GiftCardCodeHandler(),
            ]
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
    public function getFlashMessenger()
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication()
    {
        return $this->getProvidedDependency(DiscountWidgetDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCheckoutVoucherForm()
    {
        return $this->getFormFactory()->create(CheckoutVoucherForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }
}
