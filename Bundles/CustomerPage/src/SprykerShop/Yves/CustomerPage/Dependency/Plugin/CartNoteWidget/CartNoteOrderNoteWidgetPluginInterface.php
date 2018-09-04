<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Plugin\CartNoteWidget;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\CartNoteWidget\Widget\DisplayOrderNoteWidget instead.
 */
interface CartNoteOrderNoteWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CartNoteOrderNoteWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function initialize(OrderTransfer $orderTransfer): void;
}
