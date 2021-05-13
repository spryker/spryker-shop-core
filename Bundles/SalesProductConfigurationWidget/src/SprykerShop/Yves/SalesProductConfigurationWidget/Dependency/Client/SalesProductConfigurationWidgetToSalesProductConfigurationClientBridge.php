<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductConfigurationWidget\Dependency\Client;

use Generated\Shared\Transfer\OrderTransfer;

class SalesProductConfigurationWidgetToSalesProductConfigurationClientBridge implements SalesProductConfigurationWidgetToSalesProductConfigurationClientInterface
{
    /**
     * @var \Spryker\Client\SalesProductConfiguration\SalesProductConfigurationClientInterface
     */
    protected $salesProductConfigurationClient;

    /**
     * @param \Spryker\Client\SalesProductConfiguration\SalesProductConfigurationClientInterface $salesProductConfigurationClient
     */
    public function __construct($salesProductConfigurationClient)
    {
        $this->salesProductConfigurationClient = $salesProductConfigurationClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandItemsWithProductConfiguration(array $itemTransfers, OrderTransfer $orderTransfer): array
    {
        return $this->salesProductConfigurationClient->expandItemsWithProductConfiguration($itemTransfers, $orderTransfer);
    }
}
