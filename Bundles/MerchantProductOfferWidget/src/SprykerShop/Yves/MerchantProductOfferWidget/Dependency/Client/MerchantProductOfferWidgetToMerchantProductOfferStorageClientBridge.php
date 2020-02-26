<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;

class MerchantProductOfferWidgetToMerchantProductOfferStorageClientBridge implements MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     */
    public function __construct($merchantProductOfferStorageClient)
    {
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
    }

    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer|null
     */
    public function findProductOfferStorageByReference(string $productOfferReference): ?ProductOfferStorageTransfer
    {
        return $this->merchantProductOfferStorageClient->findProductOfferStorageByReference($productOfferReference);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function getProductOfferStorageCollection(ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer): ProductOfferStorageCollectionTransfer
    {
        return $this->merchantProductOfferStorageClient->getProductOfferStorageCollection($productOfferStorageCriteriaTransfer);
    }
}
