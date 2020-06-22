<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantProductViewCollectionTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface MerchantProductViewCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands and returns MerchantProductViewCollection transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductViewCollectionTransfer
     */
    public function expand(
        MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer,
        ProductViewTransfer $productViewTransfer
    ): MerchantProductViewCollectionTransfer;
}
