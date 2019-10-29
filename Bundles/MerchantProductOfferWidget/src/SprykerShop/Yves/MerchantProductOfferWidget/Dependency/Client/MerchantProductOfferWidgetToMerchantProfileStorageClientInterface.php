<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client;

use Generated\Shared\Transfer\MerchantProfileViewTransfer;

interface MerchantProductOfferWidgetToMerchantProfileStorageClientInterface
{
    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileViewTransfer|null
     */
    public function findMerchantProfileStorageViewData(int $idMerchant): ?MerchantProfileViewTransfer;
}
