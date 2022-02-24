<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;

class ProductOfferWidgetToProductOfferStorageClientBridge implements ProductOfferWidgetToProductOfferStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface
     */
    protected $productOfferStorageClient;

    /**
     * @param \Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface $productOfferStorageClient
     */
    public function __construct($productOfferStorageClient)
    {
        $this->productOfferStorageClient = $productOfferStorageClient;
    }

    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer|null
     */
    public function findProductOfferStorageByReference(string $productOfferReference): ?ProductOfferStorageTransfer
    {
        return $this->productOfferStorageClient->findProductOfferStorageByReference($productOfferReference);
    }
}
