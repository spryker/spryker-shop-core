<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Form\Handler;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;

interface MerchantRelationRequestHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function createMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer;

    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function cancelMerchantRelationRequest(string $uuid): MerchantRelationRequestCollectionResponseTransfer;
}
