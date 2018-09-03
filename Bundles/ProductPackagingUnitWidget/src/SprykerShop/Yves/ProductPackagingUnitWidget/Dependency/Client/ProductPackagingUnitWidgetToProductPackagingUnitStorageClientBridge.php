<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;

class ProductPackagingUnitWidgetToProductPackagingUnitStorageClientBridge implements ProductPackagingUnitWidgetToProductPackagingUnitStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageClientInterface
     */
    protected $productPackagingUnitStorageClient;

    /**
     * @param \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageClientInterface $productPackagingUnitStorageClient
     */
    public function __construct(
        $productPackagingUnitStorageClient
    ) {
        $this->productPackagingUnitStorageClient = $productPackagingUnitStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer|null
     */
    public function findProductAbstractPackagingById(int $idProductAbstract): ?ProductAbstractPackagingStorageTransfer
    {
        return $this->productPackagingUnitStorageClient->findProductAbstractPackagingById($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer|null
     */
    public function findProductConcretePackagingById(int $idProductAbstract, int $idProduct): ?ProductConcretePackagingStorageTransfer
    {
        return $this->productPackagingUnitStorageClient->findProductConcretePackagingById($idProductAbstract, $idProduct);
    }
}
