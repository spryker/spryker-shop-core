<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Sorter;

use Generated\Shared\Transfer\MerchantProductViewCollectionTransfer;

interface MerchantProductViewCollectionSorterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductViewCollectionTransfer
     */
    public function sort(MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer): MerchantProductViewCollectionTransfer;
}
