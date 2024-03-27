<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Reader;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantStorageClientInterface;

class MerchantStorageReader implements MerchantStorageReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantStorageClientInterface
     */
    protected MerchantRelationRequestPageToMerchantStorageClientInterface $merchantStorageClient;

    /**
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantStorageClientInterface $merchantStorageClient
     */
    public function __construct(MerchantRelationRequestPageToMerchantStorageClientInterface $merchantStorageClient)
    {
        $this->merchantStorageClient = $merchantStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findMerchantByMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): ?MerchantStorageTransfer {
        $merchantStorageCriteriaTransfer = (new MerchantStorageCriteriaTransfer())
            ->addMerchantId($merchantRelationRequestTransfer->getMerchantOrFail()->getIdMerchantOrFail());

        $merchantStorageTransfers = $this->merchantStorageClient->get($merchantStorageCriteriaTransfer);
        $merchantStorageTransfer = reset($merchantStorageTransfers);

        if (!$merchantStorageTransfer) {
            return null;
        }

        return $merchantStorageTransfer;
    }
}
