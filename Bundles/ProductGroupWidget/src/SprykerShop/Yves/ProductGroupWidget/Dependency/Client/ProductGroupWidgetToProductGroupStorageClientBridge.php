<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget\Dependency\Client;

class ProductGroupWidgetToProductGroupStorageClientBridge implements ProductGroupWidgetToProductGroupStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductGroupStorage\ProductGroupStorageClientInterface
     */
    protected $productGroupStorageClient;

    /**
     * @param \Spryker\Client\ProductGroupStorage\ProductGroupStorageClientInterface $productGroupStorageClient
     */
    public function __construct($productGroupStorageClient)
    {
        $this->productGroupStorageClient = $productGroupStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer
     */
    public function findProductGroupItemsByIdProductAbstract($idProductAbstract)
    {
        return $this->productGroupStorageClient->findProductGroupItemsByIdProductAbstract($idProductAbstract);
    }
}
