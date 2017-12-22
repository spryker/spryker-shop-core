<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductCategoryWidget\Dependency\Client;

class ProductCategoryWidgetToProductCategoryStorageClientBridge implements ProductCategoryWidgetToProductCategoryStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface
     */
    protected $productCategoryStorageClient;

    /**
     * @param \Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClientInterface $productCategoryStorageClient
     */
    public function __construct($productCategoryStorageClient)
    {
        $this->productCategoryStorageClient = $productCategoryStorageClient;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductAbstractCategory($idProductAbstract, $locale)
    {
        return $this->productCategoryStorageClient->findProductAbstractCategory($idProductAbstract, $locale);
    }
}
