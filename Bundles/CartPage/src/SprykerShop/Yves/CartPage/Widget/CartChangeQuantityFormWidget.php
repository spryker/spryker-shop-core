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
     * @var string
     */
    protected const PARAMETER_CART_CHANGE_QUANTITY_FORM = 'cartChangeQuantityForm';

    /**
     * @var string
     */
    protected const PARAMETER_CART_ITEM = 'cartItem';

    /**
     * @var string
     */
    protected const PARAMETER_READ_ONLY = 'readOnly';

    /**
     * @var string
     */
    protected const PARAMETER_NUMBER_FORMAT_CONFIG = 'numberFormatConfig';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param bool $readOnly
     */
    public function __construct(ItemTransfer $itemTransfer, bool $readOnly)
    {
        $this->addCartChangeQuantityFormParameter();
        $this->addCartItemParameter($itemTransfer);
        $this->addReadOnlyParameter($readOnly);
        $this->addNumberFormatConfigParameter();
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
     * @return void
     */
    protected function addCartChangeQuantityFormParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_CART_CHANGE_QUANTITY_FORM,
            $this->getFactory()
                ->createCartPageFormFactory()
                ->getCartChangeQuantityForm()
                ->createView(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addCartItemParameter(ItemTransfer $itemTransfer): void
    {
        $this->addParameter(static::PARAMETER_CART_ITEM, $itemTransfer);
    }

    /**
     * @param bool $readOnly
     *
     * @return void
     */
    protected function addReadOnlyParameter(bool $readOnly): void
    {
        $this->addParameter(static::PARAMETER_READ_ONLY, $readOnly);
    }

    /**
     * @return void
     */
    protected function addNumberFormatConfigParameter(): void
    {
        $numberFormatConfigTransfer = $this->getFactory()
            ->getUtilNumberService()
            ->getNumberFormatConfig(
                $this->getFactory()->getLocale(),
            );

        $this->addParameter(static::PARAMETER_NUMBER_FORMAT_CONFIG, $numberFormatConfigTransfer->toArray());
    }
}
