<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;

class ProductSearchWidgetToProductQuantityStorageClientBridge implements ProductSearchWidgetToProductQuantityStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageClientInterface
     */
    protected $productQuantityStorage;

    /**
     * @param \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageClientInterface $productQuantityStorage
     */
    public function __construct($productQuantityStorage)
    {
        $this->productQuantityStorage = $productQuantityStorage;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null
     */
    public function findProductQuantityStorage(int $idProduct): ?ProductQuantityStorageTransfer
    {
        return $this->productQuantityStorage->findProductQuantityStorage($idProduct);
    }
}
