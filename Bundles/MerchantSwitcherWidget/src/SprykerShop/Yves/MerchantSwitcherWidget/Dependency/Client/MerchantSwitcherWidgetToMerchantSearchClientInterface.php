<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client;

use Generated\Shared\Transfer\MerchantSearchRequestTransfer;

interface MerchantSwitcherWidgetToMerchantSearchClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSearchRequestTransfer $merchantSearchRequestTransfer
     *
     * @return array
     */
    public function merchantSearch(MerchantSearchRequestTransfer $merchantSearchRequestTransfer): array;
}
