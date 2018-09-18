<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;

class ProductSearchWidgetToProductPageSearchClientBridge implements ProductSearchWidgetToProductPageSearchClientInterface
{
    /**
     * @var \Spryker\Client\ProductPageSearch\ProductPageSearchClientInterface
     */
    protected $productPageSearchClient;

    /**
     * @param \Spryker\Client\ProductPageSearch\ProductPageSearchClientInterface $productPageSearchClient
     */
    public function __construct($productPageSearchClient)
    {
        $this->productPageSearchClient = $productPageSearchClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return array
     */
    public function searchProductConcretesByFullText(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): array
    {
        return $this->productPageSearchClient->searchProductConcretesByFullText($productConcreteCriteriaFilterTransfer);
    }
}
