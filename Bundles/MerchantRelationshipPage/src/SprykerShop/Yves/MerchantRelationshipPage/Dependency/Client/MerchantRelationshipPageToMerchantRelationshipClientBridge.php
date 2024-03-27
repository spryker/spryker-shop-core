<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;

class MerchantRelationshipPageToMerchantRelationshipClientBridge implements MerchantRelationshipPageToMerchantRelationshipClientInterface
{
    /**
     * @var \Spryker\Client\MerchantRelationship\MerchantRelationshipClientInterface
     */
    protected $merchantRelationshipClient;

    /**
     * @param \Spryker\Client\MerchantRelationship\MerchantRelationshipClientInterface $merchantRelationshipClient
     */
    public function __construct($merchantRelationshipClient)
    {
        $this->merchantRelationshipClient = $merchantRelationshipClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function getMerchantRelationshipCollection(
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCollectionTransfer {
        return $this->merchantRelationshipClient->getMerchantRelationshipCollection(
            $merchantRelationshipCriteriaTransfer,
        );
    }
}
