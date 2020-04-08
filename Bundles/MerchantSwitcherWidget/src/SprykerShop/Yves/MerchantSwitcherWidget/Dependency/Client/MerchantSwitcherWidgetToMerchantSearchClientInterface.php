<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client;

use Generated\Shared\Transfer\MerchantCollectionTransfer;

interface MerchantSwitcherWidgetToMerchantSearchClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchants(): MerchantCollectionTransfer;
}
