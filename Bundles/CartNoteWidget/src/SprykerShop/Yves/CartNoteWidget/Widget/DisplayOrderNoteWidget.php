<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Widget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class DisplayOrderNoteWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     */
    public function __construct(OrderTransfer $orderTransfer)
    {
        $this->addParameter('order', $orderTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'DisplayOrderNoteWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartNoteWidget/views/customer-cart-note-display/customer-cart-note-display.twig';
    }
}
