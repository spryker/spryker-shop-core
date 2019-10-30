<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferViewCollectionTransfer;

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
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductOfferViewCollectionTransfer
     */
    public function getProductOfferViewCollection(string $concreteSku): ProductOfferViewCollectionTransfer
    {
        return $this->merchantProductOfferStorageClient->getProductOfferViewCollection($concreteSku);
    }
}
