<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;

interface MerchantRelationRequestPageToMerchantRelationRequestClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function getMerchantRelationRequestCollection(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function createMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function updateMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer;
}
