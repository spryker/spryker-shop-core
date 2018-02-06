<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Plugin\CustomerReorderWidget;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CustomerReorderWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'CustomerReorderWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransfer
     *
     * @return void
     */
    public function initialize(OrderTransfer $orderTransfer, ItemTransfer $itemTransfer = null): void;
}
