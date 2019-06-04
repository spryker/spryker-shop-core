<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;

class ProductSetDetailPageToProductStorageClientBridge implements ProductSetDetailPageToProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     */
    public function __construct($productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductAbstractViewTransfer(int $idProductAbstract, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer
    {
        return $this->productStorageClient->findProductAbstractViewTransfer($idProductAbstract, $localeName, $selectedAttributes);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductAbstractViewTransfers(array $productAbstractIds, string $localeName, array $selectedAttributes = []): array
    {
        return $this->productStorageClient->getProductAbstractViewTransfers($productAbstractIds, $localeName, $selectedAttributes);
    }
}
