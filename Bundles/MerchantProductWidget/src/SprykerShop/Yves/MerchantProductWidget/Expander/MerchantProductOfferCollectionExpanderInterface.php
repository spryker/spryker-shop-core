<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Expander;

interface MerchantProductOfferCollectionExpanderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     * @param string $locale
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    public function expand(array $productOfferTransfers, string $locale): array;
}
