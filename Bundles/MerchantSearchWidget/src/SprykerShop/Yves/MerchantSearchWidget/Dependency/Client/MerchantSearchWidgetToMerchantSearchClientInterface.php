<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSearchWidget\Dependency\Client;

use Generated\Shared\Transfer\MerchantSearchRequestTransfer;

interface MerchantSearchWidgetToMerchantSearchClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSearchRequestTransfer $merchantSearchRequestTransfer
     *
     * @return array<mixed>
     */
    public function search(MerchantSearchRequestTransfer $merchantSearchRequestTransfer): array;
}
