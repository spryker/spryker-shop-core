<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class DisplayOrderItemNoteWidget extends AbstractWidget
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
        return 'DisplayOrderItemNoteWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartNoteWidget/views/customer-cart-item-note-display/customer-cart-item-note-display.twig';
    }
}
