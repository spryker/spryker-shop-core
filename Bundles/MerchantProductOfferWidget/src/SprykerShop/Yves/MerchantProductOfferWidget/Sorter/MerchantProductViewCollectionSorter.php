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
    public function sort(MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer): array
    {
        $coutElements = count($merchantProductViewCollectionTransfer);
        $iterations = $coutElements - 1;

        for ($i = 0; $i < $coutElements; $i++) {
            $changes = false;

            for ($j = 0; $j < $iterations; $j++) {
                $nextKey = $j + 1;
                if ($merchantProductViewCollectionTransfer[$j]->getPrice() > $merchantProductViewCollectionTransfer[$nextKey]->getPrice()) {
                    $changes = true;
                    list($merchantProductViewCollectionTransfer[$j], $merchantProductViewCollectionTransfer[$nextKey]) = [$merchantProductViewCollectionTransfer[$nextKey], $merchantProductViewCollectionTransfer[$j]];
                }
            }
            $iterations--;

            if (!$changes) {
                return $merchantProductViewCollectionTransfer;
            }
        }

        return $merchantProductViewCollectionTransfer;
    }
}
