<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;

class MerchantSwitcherWidgetToMerchantSearchClientBridge implements MerchantSwitcherWidgetToMerchantSearchClientInterface
{
    /**
     * @var \Spryker\Client\MerchantSearch\MerchantSearchClientInterface
     */
    protected $merchantSearchClient;

    /**
     * @param \Spryker\Client\MerchantSearch\MerchantSearchClientInterface $merchantSearchClient
     */
    public function __construct($merchantSearchClient)
    {
        $this->merchantSearchClient = $merchantSearchClient;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchantCollection(): MerchantCollectionTransfer
    {
        return $this->merchantSearchClient->getMerchantCollection();
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    public function merchantSearch(): MerchantSearchCollectionTransfer
    {
        return $this->merchantSearchClient->merchantSearch();
    }
}
