<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductRelationWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class ProductRelationWidgetToProductRelationStorageClientBridge implements ProductRelationWidgetToProductRelationStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductRelationStorage\ProductRelationStorageClientInterface
     */
    protected $productRelationStorageClient;

    /**
     * @param \Spryker\Client\ProductRelationStorage\ProductRelationStorageClientInterface $productRelationStorageClient
     */
    public function __construct($productRelationStorageClient)
    {
        $this->productRelationStorageClient = $productRelationStorageClient;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findRelatedProducts($idProductAbstract, $localeName, string $storeName)
    {
        return $this->productRelationStorageClient->findRelatedProducts($idProductAbstract, $localeName, $storeName);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findUpSellingProducts(QuoteTransfer $quoteTransfer, $localeName)
    {
        return $this->productRelationStorageClient->findUpSellingProducts($quoteTransfer, $localeName);
    }
}
