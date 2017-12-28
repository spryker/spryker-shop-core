<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetListPage\Dependency\Client;

class ProductSetListPageToProductSetPageSearchClientBridge implements ProductSetListPageToProductSetPageSearchClientInterface
{
    /**
     * @var \Spryker\Client\ProductSetPageSearch\ProductSetPageSearchClientInterface
     */
    protected $productSetPageSearchClient;

    /**
     * @param \Spryker\Client\ProductSetPageSearch\ProductSetPageSearchClientInterface $productSetPageSearchClient
     */
    public function __construct($productSetPageSearchClient)
    {
        $this->productSetPageSearchClient = $productSetPageSearchClient;
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getProductSetList($limit = null, $offset = null)
    {
        return $this->productSetPageSearchClient->getProductSetList($limit, $offset);
    }
}
