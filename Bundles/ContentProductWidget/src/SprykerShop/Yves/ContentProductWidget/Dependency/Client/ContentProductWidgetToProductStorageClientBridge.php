<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;

class ContentProductWidgetToProductStorageClientBridge implements ContentProductWidgetToProductStorageClientBridgeInterface
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
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
    {
        return $this->productStorageClient->findProductAbstractStorageData($idProductAbstract, $localeName);
    }

    /**
     * @param array $data
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductAbstractStorageData(array $data, string $localeName, array $selectedAttributes = []): ProductViewTransfer
    {
        return $this->productStorageClient->mapProductStorageData($data, $localeName, $selectedAttributes);
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
