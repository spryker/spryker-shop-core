<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\VolumePriceProductWidget\Business\VolumePriceProduct;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer;

interface VolumePriceProductResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer
     */
    public function resolveVolumePriceProduct(ProductViewTransfer $productViewTransfer): VolumeProductPriceCollectionTransfer;
}
