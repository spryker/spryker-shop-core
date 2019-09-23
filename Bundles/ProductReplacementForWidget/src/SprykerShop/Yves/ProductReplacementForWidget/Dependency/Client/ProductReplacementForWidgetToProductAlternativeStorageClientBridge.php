<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReplacementForWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductReplacementStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class ProductReplacementForWidgetToProductAlternativeStorageClientBridge implements ProductReplacementForWidgetToProductAlternativeStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductAlternativeStorage\ProductAlternativeStorageClientInterface
     */
    protected $productAlternativeStorageClient;

    /**
     * @param \Spryker\Client\ProductAlternativeStorage\ProductAlternativeStorageClientInterface $productAlternativeStorageClient
     */
    public function __construct($productAlternativeStorageClient)
    {
        $this->productAlternativeStorageClient = $productAlternativeStorageClient;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductReplacementStorageTransfer|null
     */
    public function findProductReplacementForStorage(string $sku): ?ProductReplacementStorageTransfer
    {
        return $this->productAlternativeStorageClient->findProductReplacementForStorage($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(ProductViewTransfer $productViewTransfer): bool
    {
        return $this->productAlternativeStorageClient->isAlternativeProductApplicable($productViewTransfer);
    }
}
