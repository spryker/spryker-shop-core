<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;

interface MerchantProductOfferWidgetToMerchantProfileStorageClientInterface
{
    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer|null
     */
    public function findMerchantProfileStorageData(int $idMerchant): ?MerchantProfileStorageTransfer;

    /**
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer[]
     */
    public function findMerchantProfileStorageList(array $merchantIds): array;
}
