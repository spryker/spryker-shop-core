<?php

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;

interface ProductMeasurementUnitWidgetToProductQuantityStorageClientInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null
     */
    public function findProductQuantityStorage(int $idProduct): ?ProductQuantityStorageTransfer;
}
