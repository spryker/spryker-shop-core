<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDiscontinuedWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;

class ProductDiscontinuedWidgetToProductDiscontinuedStorageClientBridge implements ProductDiscontinuedWidgetToProductDiscontinuedStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface
     */
    protected $productDiscontinuedStorageClient;

    /**
     * @param \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface $productDiscontinuedStorageClient
     */
    public function __construct($productDiscontinuedStorageClient)
    {
        $this->productDiscontinuedStorageClient = $productDiscontinuedStorageClient;
    }

    /**
     * @param string $concreteSku
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer|null
     */
    public function findProductDiscontinuedStorage(string $concreteSku, string $locale): ?ProductDiscontinuedStorageTransfer
    {
        return $this->productDiscontinuedStorageClient->findProductDiscontinuedStorage($concreteSku, $locale);
    }
}
