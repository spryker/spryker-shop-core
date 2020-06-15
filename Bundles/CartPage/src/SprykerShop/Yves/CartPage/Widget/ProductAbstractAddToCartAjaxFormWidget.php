<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class ProductAbstractAddToCartAjaxFormWidget extends AbstractWidget
{
    protected const PARAMETER_ADD_TO_CART_FORM = 'addToCartForm';
    protected const PARAMETER_PRODUCT_ABSTRACT = 'productAbstract';

    /**
     * @param array $productAbstract
     */
    public function __construct(array $productAbstract)
    {
        $this->addAddToCartFormParameter();
        $this->addProductAbstractParameter($productAbstract);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductAbstractAddToCartAjaxFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartPage/views/product-abstract-add-to-cart-ajax-form/product-abstract-add-to-cart-ajax-form.twig';
    }

    /**
     * @return void
     */
    protected function addAddToCartFormParameter(): void
    {
        $addToCartForm = $this->getFactory()
            ->createCartPageFormFactory()
            ->getAddToCartForm()
            ->createView();

        $this->addParameter(static::PARAMETER_ADD_TO_CART_FORM, $addToCartForm);
    }

    /**
     * @param array $productAbstract
     *
     * @return void
     */
    protected function addProductAbstractParameter(array $productAbstract): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT_ABSTRACT, $productAbstract);
    }
}
