<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantity\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;

class ProductQuantityToProductQuantityStorageClientBridge implements ProductQuantityToProductQuantityStorageClientInterface
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
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransferWithQuantityRestrictions(
        ProductViewTransfer $productViewTransfer
    ): ProductViewTransfer {
        return $this->productQuantityStorageClient->expandProductViewTransferWithQuantityRestrictions($productViewTransfer);
    }
}
