<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductVolumeWidget\Business\PriceProductVolume;

use Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface PriceProductVolumeResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer
     */
    public function resolveVolumeProductPrices(ProductViewTransfer $productViewTransfer): PriceProductVolumeCollectionTransfer;
}
