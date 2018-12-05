<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;

class CartPageToProductStorageClientBridge implements CartPageToProductStorageClientInterface
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
     * @param int $idProductConcrete
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductAbstractViewTransfer(int $idProductConcrete, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer
    {
        return $this->productStorageClient->findProductAbstractViewTransfer($idProductConcrete, $localeName, $selectedAttributes);
    }
}
