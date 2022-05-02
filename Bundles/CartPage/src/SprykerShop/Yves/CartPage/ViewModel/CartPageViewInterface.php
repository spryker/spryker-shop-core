<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\ViewModel;

use Generated\Shared\Transfer\CartPageViewArgumentsTransfer;

interface CartPageViewInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartPageViewArgumentsTransfer $cartPageViewArgumentsTransfer
     *
     * @return array<string, mixed>
     */
    public function getViewData(CartPageViewArgumentsTransfer $cartPageViewArgumentsTransfer): array;
}
