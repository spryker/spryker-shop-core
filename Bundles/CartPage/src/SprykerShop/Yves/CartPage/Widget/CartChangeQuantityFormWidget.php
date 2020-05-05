<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartChangeQuantityFormWidget extends AbstractWidget
{
    /**
     * @var \Symfony\Component\Form\FormView|null
     */
    protected static $cartChangeQuantityFormView;

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartItem
     * @param bool $readOnly
     */
    public function __construct(ItemTransfer $cartItem, bool $readOnly)
    {
        $this->addParameter('cartChangeQuantityForm', $this->getOrCreateCartChangeQuantityFormView());
        $this->addParameter('cartItem', $cartItem);
        $this->addParameter('readOnly', $readOnly);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartChangeQuantityFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartPage/views/cart-change-quantity-form/cart-change-quantity-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function createCartChangeQuantityFormView(): FormView
    {
        return $this->getFactory()
            ->createCartPageFormFactory()
            ->getCartChangeQuantityForm()
            ->createView();
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getOrCreateCartChangeQuantityFormView(): FormView
    {
        if (static::$cartChangeQuantityFormView === null) {
            static::$cartChangeQuantityFormView = $this->createCartChangeQuantityFormView();
        }

        return static::$cartChangeQuantityFormView;
    }
}
