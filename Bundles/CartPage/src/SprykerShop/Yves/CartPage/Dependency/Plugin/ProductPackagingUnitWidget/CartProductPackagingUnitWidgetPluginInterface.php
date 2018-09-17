<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Dependency\Plugin\ProductPackagingUnitWidget;

use Generated\Shared\Transfer\ItemTransfer;

interface CartProductPackagingUnitWidgetPluginInterface
{
    public const NAME = 'CartProductPackagingUnitWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(ItemTransfer $itemTransfer): void;
}
