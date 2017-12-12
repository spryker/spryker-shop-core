<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Dependency\Client;

class ProductOptionWidgetToProductOptionClientBridge implements ProductOptionWidgetToProductOptionClientInterface
{
    /**
     * @var \Spryker\Client\ProductOption\ProductOptionClientInterface
     */
    protected $productOptionClient;

    /**
     * @param \Spryker\Client\ProductOption\ProductOptionClientInterface $productOptionClient
     */
    public function __construct($productOptionClient)
    {
        $this->productOptionClient = $productOptionClient;
    }

    /**
     * @param int $idAbstractProduct
     * @param int $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductOptionGroupCollectionTransfer
     */
    public function getProductOptions($idAbstractProduct, $localeName)
    {
        return $this->productOptionClient->getProductOptions($idAbstractProduct, $localeName);
    }
}
