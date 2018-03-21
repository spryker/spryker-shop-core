<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Symfony\Component\HttpFoundation\Request;

interface CartItemBeforeAddPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function add(ItemTransfer $itemTransfer, Request $request): ItemTransfer;
}
