<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetListPage\Dependency\Client;

class ProductSetListPageToProductSetClientBridge implements ProductSetListPageToProductSetClientInterface
{
    /**
     * @var \Spryker\Client\ProductSet\ProductSetClientInterface
     */
    protected $productSetClient;

    /**
     * @param \Spryker\Client\ProductSet\ProductSetClientInterface $productSetClient
     */
    public function __construct($productSetClient)
    {
        $this->productSetClient = $productSetClient;
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getProductSetList($limit = null, $offset = null)
    {
        return $this->productSetClient->getProductSetList($limit, $offset);
    }
}
