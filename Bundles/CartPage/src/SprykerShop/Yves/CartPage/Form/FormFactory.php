<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Form;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Form\FormFactory as SymfonyFormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class FormFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): SymfonyFormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getRemoveForm(): FormInterface
    {
        return $this->getFormFactory()->create(RemoveForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAddToCartForm(): FormInterface
    {
        return $this->getFormFactory()->create(AddToCartForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAddItemsForm(): FormInterface
    {
        return $this->getFormFactory()->create(AddItemsForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCartChangeQuantityForm(): FormInterface
    {
        return $this->getFormFactory()->create(CartChangeQuantityForm::class);
    }
}
