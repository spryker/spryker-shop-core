<?php

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;

class ProductMeasurementUnitWidgetToProductQuantityStorageClientBridge implements ProductMeasurementUnitWidgetToProductQuantityStorageClientInterface
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
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null
     */
    public function findProductQuantityStorage(int $idProduct): ?ProductQuantityStorageTransfer
    {
        return $this->productQuantityStorageClient->findProductQuantityStorage($idProduct);
    }
}
