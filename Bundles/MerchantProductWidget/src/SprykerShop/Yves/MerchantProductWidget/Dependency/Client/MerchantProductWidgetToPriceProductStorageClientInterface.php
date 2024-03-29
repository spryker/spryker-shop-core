<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Dependency\Client;

interface MerchantProductWidgetToPriceProductStorageClientInterface
{
    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getResolvedPriceProductConcreteTransfers(int $idProductConcrete, int $idProductAbstract): array;
}
