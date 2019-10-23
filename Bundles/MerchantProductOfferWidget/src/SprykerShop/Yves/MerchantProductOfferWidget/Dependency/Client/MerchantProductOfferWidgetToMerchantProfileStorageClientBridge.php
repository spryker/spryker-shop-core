<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferViewCollectionTransfer;
use Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface;

class MerchantProductOfferWidgetToMerchantProfileStorageClientBridge implements MerchantProductOfferWidgetToMerchantProfileStorageClientInterface
{
    /**
     * @var \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @param \Spryker\Client\MerchantProductOfferStorage\MerchantProductOfferStorageClientInterface
     */
    public function __construct(MerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient)
    {
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
    }

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductOfferViewCollectionTransfer|null
     */
    public function findProductOffersByConcreteSku(string $concreteSku): ?ProductOfferViewCollectionTransfer
    {
        $this->merchantProductOfferStorageClient->findProductOffersByConcreteSku($concreteSku);
    }
 }
