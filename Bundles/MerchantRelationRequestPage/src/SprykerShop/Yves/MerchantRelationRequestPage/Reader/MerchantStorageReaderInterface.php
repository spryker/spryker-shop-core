<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Reader;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;

interface MerchantStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findMerchantByMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): ?MerchantStorageTransfer;
}
