<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Sorter;

use Generated\Shared\Transfer\MerchantProductViewCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductViewTransfer;

class MerchantProductViewCollectionSorter implements MerchantProductViewCollectionSorterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductViewCollectionTransfer
     */
    public function sort(MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer): MerchantProductViewCollectionTransfer
    {
        $merchantProductViewCollectionTransfer
            ->getMerchantProductViews()
            ->uasort(function (MerchantProductViewTransfer $a, MerchantProductViewTransfer $b) {
                if ($a->getPrice()->getPrice() == $b->getPrice()->getPrice()) {
                    return 0;
                }

                return $a->getPrice()->getPrice() < $b->getPrice()->getPrice() ? -1 : 1;
            });

        return $merchantProductViewCollectionTransfer;
    }
}
