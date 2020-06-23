<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Mapper;

use Generated\Shared\Transfer\MerchantProductViewTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;

class MerchantProductViewMapper
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param \Generated\Shared\Transfer\MerchantProductViewTransfer $merchantProductViewTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductViewTransfer
     */
    public function mapProductOfferStorageTransferToMerchantProductViewTransfer(
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        MerchantProductViewTransfer $merchantProductViewTransfer
    ): MerchantProductViewTransfer {
        return $merchantProductViewTransfer->fromArray($productOfferStorageTransfer->toArray(), true)
            ->setMerchantName($productOfferStorageTransfer->getMerchantStorage()->getName());
    }
}
