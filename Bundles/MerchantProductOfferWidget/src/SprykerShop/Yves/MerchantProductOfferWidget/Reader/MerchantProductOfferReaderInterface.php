<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Reader;

use Generated\Shared\Transfer\ProductViewTransfer;

interface MerchantProductOfferReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function getProductOffers(ProductViewTransfer $productViewTransfer, string $localeName): array;

    /**
     * @param string $productOfferReference
     *
     * @return string|null
     */
    public function findMerchantReferenceByProductOfferReference(string $productOfferReference): ?string;
}
