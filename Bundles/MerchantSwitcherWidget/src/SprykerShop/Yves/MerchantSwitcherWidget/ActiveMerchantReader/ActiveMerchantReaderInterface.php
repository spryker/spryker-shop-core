<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\ActiveMerchantReader;

use Generated\Shared\Transfer\MerchantCollectionTransfer;

interface ActiveMerchantReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getActiveMerchants(): MerchantCollectionTransfer;
}
