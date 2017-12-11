<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Dependency\Client;

class ProductSetDetailPageToProductSetClientBridge implements ProductSetDetailPageToProductSetClientInterface
{
    /**
     * @var \Spryker\Client\ProductSet\ProductSetClientInterface
     */
    protected $productSetClient;

    /**
     * @param \Spryker\Client\ProductSet\ProductSetClientInterface $productSetClient
     */
    public function __construct($productSetClient)
    {
        $this->productSetClient = $productSetClient;
    }

    /**
     * @param array $productSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    public function mapProductSetStorageDataToTransfer(array $productSetStorageData)
    {
        return $this->productSetClient->mapProductSetStorageDataToTransfer($productSetStorageData);
    }
}
