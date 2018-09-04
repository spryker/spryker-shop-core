<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class DisplayCartItemNoteWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer)
    {
        $this->addParameter('item', $itemTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'DisplayCartItemNoteWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartNoteWidget/views/checkout-cart-item-note-display/checkout-cart-item-note-display.twig';
    }
}
