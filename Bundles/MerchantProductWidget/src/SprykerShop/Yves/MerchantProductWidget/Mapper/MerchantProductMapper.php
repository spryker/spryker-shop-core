<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Mapper;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;
use Generated\Shared\Transfer\MerchantProductViewTransfer;

class MerchantProductMapper
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductStorageTransfer $merchantProductStorageTransfer
     * @param \Generated\Shared\Transfer\MerchantProductViewTransfer $merchantProductViewTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductViewTransfer
     */
    public function mapMerchantProductStorageTransferToMerchantProductViewTransfer(
        MerchantProductStorageTransfer $merchantProductStorageTransfer,
        MerchantProductViewTransfer $merchantProductViewTransfer
    ): MerchantProductViewTransfer {
        return $merchantProductViewTransfer->fromArray($merchantProductStorageTransfer->toArray(), true);
    }
}
