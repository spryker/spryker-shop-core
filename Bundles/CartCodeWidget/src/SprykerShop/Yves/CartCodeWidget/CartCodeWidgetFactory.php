<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CartCodeWidget\Form\CartCodeForm;
use SprykerShop\Yves\CartCodeWidget\Dependency\Client\CartCodeWidgetToCartCodeClientInterface;
use SprykerShop\Yves\CartCodeWidget\Dependency\Client\CartCodeWidgetToQuoteClientInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class CartCodeWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CartCodeWidget\Dependency\Client\CartCodeWidgetToCartCodeClientInterface
     */
    public function getCartCodeClient(): CartCodeWidgetToCartCodeClientInterface
    {
        return $this->getProvidedDependency(CartCodeWidgetDependencyProvider::CLIENT_CART_CODE);
    }

    /**
     * @return \SprykerShop\Yves\CartCodeWidget\Dependency\Client\CartCodeWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): CartCodeWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(CartCodeWidgetDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCartCodeForm(): FormInterface
    {
        return $this->getFormFactory()->create(CartCodeForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }
}
