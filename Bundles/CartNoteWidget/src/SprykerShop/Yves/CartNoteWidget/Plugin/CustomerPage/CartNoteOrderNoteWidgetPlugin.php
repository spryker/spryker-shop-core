<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\CustomerPage;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CustomerPage\Dependency\Plugin\CartNoteWidget\CartNoteOrderNoteWidgetPluginInterface;

/**
 * @deprecated Use molecule('note-list', 'CartNoteWidget') instead.
 */
class CartNoteOrderNoteWidgetPlugin extends AbstractWidgetPlugin implements CartNoteOrderNoteWidgetPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function initialize(OrderTransfer $orderTransfer): void
    {
        $this->addParameter('order', $orderTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartNoteWidget/views/customer-cart-note-display/customer-cart-note-display.twig';
    }
}
