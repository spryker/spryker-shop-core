<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Sorter;

use Generated\Shared\Transfer\MerchantProductViewCollectionTransfer;

class MerchantProductViewCollectionSorter implements MerchantProductViewCollectionSorterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer
     *
     * @return array
     */
    public function sort(MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer): MerchantProductViewCollectionTransfer
    {
        $merchantProductViewTransfers = $merchantProductViewCollectionTransfer->getMerchantProductViews();
        $coutElements = count($merchantProductViewTransfers);
        $iterations = $coutElements - 1;

        for ($i = 0; $i < $coutElements; $i++) {
            $changes = false;

            for ($j = 0; $j < $iterations; $j++) {
                $nextKey = $j + 1;
                if ($merchantProductViewTransfers[$j]->getPrice() > $merchantProductViewTransfers[$nextKey]->getPrice()) {
                    $changes = true;
                    list($merchantProductViewTransfers[$j], $merchantProductViewTransfers[$nextKey]) = [$merchantProductViewTransfers[$nextKey], $merchantProductViewCollectionTransfer[$j]];
                }
            }
            $iterations--;

            if (!$changes) {
                break;
            }
        }

        return  $merchantProductViewCollectionTransfer->setMerchantProductViews($merchantProductViewTransfers);
    }
}
