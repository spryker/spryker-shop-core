<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;

interface MerchantRelationshipPageToMerchantRelationshipClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function getMerchantRelationshipCollection(
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCollectionTransfer;
}
