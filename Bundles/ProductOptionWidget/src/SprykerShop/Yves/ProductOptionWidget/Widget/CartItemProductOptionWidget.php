<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class CartItemProductOptionWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void
    {
        $this->addParameter('cartItem', $itemTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CartItemProductOptionWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductOptionWidget/views/option-display/option-display.twig';
    }
}
