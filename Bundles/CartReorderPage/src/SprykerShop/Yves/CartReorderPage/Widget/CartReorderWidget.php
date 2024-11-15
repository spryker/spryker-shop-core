<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartReorderPage\Widget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CartReorderPage\CartReorderPageFactory getFactory()
 */
class CartReorderWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_ORDER = 'order';

    /**
     * @var string
     */
    protected const PARAMETER_FORM = 'form';

    /**
     * @var string
     */
    protected const PARAMETER_BUTTON_CLASS = 'buttonClass';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string|null $buttonClass
     */
    public function __construct(OrderTransfer $orderTransfer, ?string $buttonClass = 'button')
    {
        $this->addFormParameter();
        $this->addOrderParameter($orderTransfer);
        $this->addButtonClassParameter($buttonClass);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartReorderWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartReorderPage/views/cart-reorder/cart-reorder.twig';
    }

    /**
     * @return void
     */
    protected function addFormParameter(): void
    {
        $this->addParameter(static::PARAMETER_FORM, $this->getFactory()->getCartReorderForm()->createView());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addOrderParameter(OrderTransfer $orderTransfer): void
    {
        $this->addParameter(static::PARAMETER_ORDER, $orderTransfer);
    }

    /**
     * @param string|null $buttonClass
     *
     * @return void
     */
    protected function addButtonClassParameter(?string $buttonClass = 'button'): void
    {
        $this->addParameter(static::PARAMETER_BUTTON_CLASS, $buttonClass);
    }
}
