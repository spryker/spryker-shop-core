<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Dependency\Client;

use Generated\Shared\Transfer\ProductCategoryFilterStorageTransfer;

class CatalogPageToProductCategoryFilterStorageClientBridge implements CatalogPageToProductCategoryFilterStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductCategoryFilterStorage\ProductCategoryFilterStorageClientInterface
     */
    protected $productCategoryFilterStorageClient;

    /**
     * @param \Spryker\Client\ProductCategoryFilterStorage\ProductCategoryFilterStorageClientInterface $productCategoryFilterStorageClient
     */
    public function __construct($productCategoryFilterStorageClient)
    {
        $this->productCategoryFilterStorageClient = $productCategoryFilterStorageClient;
    }

    /**
     * @param int $idCategory
     *
     * @return ProductCategoryFilterStorageTransfer|null
     */
    public function getProductCategoryFilterByIdCategory($idCategory)
    {
        return $this->productCategoryFilterStorageClient->getProductCategoryFilterByIdCategory($idCategory);
    }

}
