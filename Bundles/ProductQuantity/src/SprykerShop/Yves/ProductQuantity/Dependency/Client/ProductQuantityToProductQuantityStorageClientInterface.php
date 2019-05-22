<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantity\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductQuantityToProductQuantityStorageClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransferWithQuantityRestrictions(
        ProductViewTransfer $productViewTransfer
    ): ProductViewTransfer;
}
