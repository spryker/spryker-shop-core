<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer;

class ProductPackagingUnitWidgetToProductPackagingUnitStorageClientBridge implements ProductPackagingUnitWidgetToProductPackagingUnitStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageClientInterface
     */
    protected $productPackagingUnitStorageClient;

    /**
     * @param \Spryker\Client\ProductPackagingUnitStorage\ProductPackagingUnitStorageClientInterface $productPackagingUnitStorageClient
     */
    public function __construct($productPackagingUnitStorageClient)
    {
        $this->productPackagingUnitStorageClient = $productPackagingUnitStorageClient;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer|null
     */
    public function findProductPackagingUnitById(int $idProductConcrete): ?ProductPackagingUnitStorageTransfer
    {
        return $this->productPackagingUnitStorageClient->findProductPackagingUnitById($idProductConcrete);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithDefaultPackagingUnit(ItemTransfer $itemTransfer): ItemTransfer
    {
        return $this->productPackagingUnitStorageClient->expandItemTransferWithDefaultPackagingUnit($itemTransfer);
    }
}
