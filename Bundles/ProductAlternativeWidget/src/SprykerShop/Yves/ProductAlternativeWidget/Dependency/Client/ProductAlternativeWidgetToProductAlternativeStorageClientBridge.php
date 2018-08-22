<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductAlternativeWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;

class ProductAlternativeWidgetToProductAlternativeStorageClientBridge implements ProductAlternativeWidgetToProductAlternativeStorageClientInterface
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
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(ProductViewTransfer $productViewTransfer): bool
    {
        return $this->productAlternativeStorageClient->isAlternativeProductApplicable($productViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getConcreteAlternativeProducts(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        return $this->productAlternativeStorageClient->getConcreteAlternativeProducts($productViewTransfer, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getAlternativeProducts(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        return $this->productAlternativeStorageClient->getAlternativeProducts($productViewTransfer, $localeName);
    }
}
