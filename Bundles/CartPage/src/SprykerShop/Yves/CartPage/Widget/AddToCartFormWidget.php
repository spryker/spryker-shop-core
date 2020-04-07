<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class AddToCartFormWidget extends AbstractWidget
{
    /**
     * @param array $config
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isAddToCartDisabled
     * @param array $quantityOptions Contains the selectable quantity options; each option is structured as ['label' => 1, 'value' => 1]
     */
    public function __construct(array $config, ProductViewTransfer $productViewTransfer, bool $isAddToCartDisabled, array $quantityOptions = [])
    {
        $this->addParameter('addToCartForm', $this->getAddToCartFormView());
        $this->addParameter('config', $config);
        $this->addParameter('product', $productViewTransfer);
        $this->addParameter('isAddToCartDisabled', $isAddToCartDisabled);
        $this->addParameter('quantityOptions', $quantityOptions);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AddToCartFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartPage/views/add-to-cart-form/add-to-cart-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getAddToCartFormView(): FormView
    {
        return $this->getFactory()
            ->createCartPageFormFactory()
            ->getAddToCartForm()
            ->createView();
    }
}
