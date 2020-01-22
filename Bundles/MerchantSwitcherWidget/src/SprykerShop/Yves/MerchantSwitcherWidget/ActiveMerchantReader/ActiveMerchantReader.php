<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\ActiveMerchantReader;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;

class ActiveMerchantReader implements ActiveMerchantReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface
     */
    protected $merchantSearchClient;

    /**
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient
     */
    public function __construct(MerchantSwitcherWidgetToMerchantSearchClientInterface $merchantSearchClient)
    {
        $this->merchantSearchClient = $merchantSearchClient;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getActiveMerchants(): MerchantCollectionTransfer
    {
        return $this->merchantSearchClient->getActiveMerchants();
    }
}
