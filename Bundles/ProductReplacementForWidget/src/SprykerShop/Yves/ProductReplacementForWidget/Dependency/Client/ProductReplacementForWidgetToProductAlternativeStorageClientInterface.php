<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReplacementForWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductReplacementStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductReplacementForWidgetToProductAlternativeStorageClientInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductReplacementStorageTransfer|null
     */
    public function findProductReplacementForStorage(string $sku): ?ProductReplacementStorageTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(ProductViewTransfer $productViewTransfer): bool;
}
