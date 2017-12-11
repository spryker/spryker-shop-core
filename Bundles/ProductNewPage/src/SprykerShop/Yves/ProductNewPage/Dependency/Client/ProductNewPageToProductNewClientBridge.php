<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage\Dependency\Client;

class ProductNewPageToProductNewClientBridge implements ProductNewPageToProductNewClientInterface
{
    /**
     * @var \Spryker\Client\ProductNew\ProductNewClientInterface
     */
    protected $productNewClient;

    /**
     * @param \Spryker\Client\ProductNew\ProductNewClientInterface $productNewClient
     */
    public function __construct($productNewClient)
    {
        $this->productNewClient = $productNewClient;
    }

    /**
     * @param array $requestParameters
     *
     * @return array
     */
    public function findNewProducts(array $requestParameters = [])
    {
        return $this->productNewClient->findNewProducts($requestParameters);
    }
}
