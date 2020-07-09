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
class RemoveFromCartFormWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartItem
     */
    public function __construct(ItemTransfer $cartItem)
    {
        $this->addParameter('removeFromCartForm', $this->createRemoveFromCartFormView());
        $this->addParameter('cartItem', $cartItem);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'RemoveFromCartFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartPage/views/remove-from-cart-form/remove-from-cart-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function createRemoveFromCartFormView(): FormView
    {
        return $this->getFactory()
            ->createCartPageFormFactory()
            ->getRemoveForm()
            ->createView();
    }
}
