<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Dependency\Client;

class ProductSetDetailPageToProductSetStorageClientBridge implements ProductSetDetailPageToProductSetStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductSetStorage\ProductSetStorageClientInterface
     */
    protected $productSetStorageClient;

    /**
     * @param \Spryker\Client\ProductSetStorage\ProductSetStorageClientInterface $productSetStorageClient
     */
    public function __construct($productSetStorageClient)
    {
        $this->productSetStorageClient = $productSetStorageClient;
    }

    /**
     * @param array $productSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    public function mapProductSetStorageDataToTransfer(array $productSetStorageData)
    {
        return $this->productSetStorageClient->mapProductSetStorageDataToTransfer($productSetStorageData);
    }
}
