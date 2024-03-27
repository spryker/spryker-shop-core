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

class MerchantRelationRequestPageToMerchantRelationRequestClientBridge implements MerchantRelationRequestPageToMerchantRelationRequestClientInterface
{
    /**
     * @var \Spryker\Client\MerchantRelationRequest\MerchantRelationRequestClientInterface
     */
    protected $merchantRelationRequestClient;

    /**
     * @param \Spryker\Client\MerchantRelationRequest\MerchantRelationRequestClientInterface $merchantRelationRequestClient
     */
    public function __construct($merchantRelationRequestClient)
    {
        $this->merchantRelationRequestClient = $merchantRelationRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function getMerchantRelationRequestCollection(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCollectionTransfer {
        return $this->merchantRelationRequestClient->getMerchantRelationRequestCollection(
            $merchantRelationRequestCriteriaTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function createMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        return $this->merchantRelationRequestClient->createMerchantRelationRequestCollection(
            $merchantRelationRequestCollectionRequestTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function updateMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        return $this->merchantRelationRequestClient->updateMerchantRelationRequestCollection(
            $merchantRelationRequestCollectionRequestTransfer,
        );
    }
}
