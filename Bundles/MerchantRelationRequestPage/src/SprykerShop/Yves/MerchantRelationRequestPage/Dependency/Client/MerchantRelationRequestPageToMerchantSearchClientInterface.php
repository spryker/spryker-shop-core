<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client;

use Generated\Shared\Transfer\MerchantSearchRequestTransfer;

interface MerchantRelationRequestPageToMerchantSearchClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSearchRequestTransfer $merchantSearchRequestTransfer
     *
     * @return mixed
     */
    public function search(MerchantSearchRequestTransfer $merchantSearchRequestTransfer);
}
