<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;

class ProductQuantityWidgetToProductQuantityStorageClientBridge implements ProductQuantityWidgetToProductQuantityStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageClientInterface
     */
    protected $productQuantityStorageClient;

    /**
     * @param \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageClientInterface $productQuantityStorageClient
     */
    public function __construct($productQuantityStorageClient)
    {
        $this->productQuantityStorageClient = $productQuantityStorageClient;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer
     */
    public function getProductQuantityStorage(int $idProduct): ProductQuantityStorageTransfer
    {
        return $this->productQuantityStorageClient->getProductQuantityStorage($idProduct);
    }
}
