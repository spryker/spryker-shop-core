<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Dependency\Client;

class ProductOptionWidgetToProductOptionStorageClientBridge implements ProductOptionWidgetToProductOptionStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductOptionStorage\ProductOptionStorageClientInterface
     */
    protected $productOptionStorageClient;

    /**
     * @param \Spryker\Client\ProductOptionStorage\ProductOptionStorageClientInterface $productOptionStorageClient
     */
    public function __construct($productOptionStorageClient)
    {
        $this->productOptionStorageClient = $productOptionStorageClient;
    }

    /**
     * @param int $idAbstractProduct
     * @param int $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    public function getProductOptions($idAbstractProduct, $localeName)
    {
        return $this->productOptionStorageClient->getProductOptions($idAbstractProduct, $localeName);
    }
}
