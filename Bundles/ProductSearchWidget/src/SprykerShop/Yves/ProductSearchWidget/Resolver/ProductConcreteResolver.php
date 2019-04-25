<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Resolver;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductStorageClientInterface;

class ProductConcreteResolver implements ProductConcreteResolverInterface
{
    protected const MAPPING_TYPE_SKU = 'sku';
    protected const ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const NAME = 'name';

    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductStorageClientInterface $productStorageClient
     */
    public function __construct(ProductSearchWidgetToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteBySku(string $sku): ?ProductConcreteTransfer
    {
        $productConcreteStorageData = $this->productStorageClient
            ->findProductConcreteStorageDataByMappingForCurrentLocale(static::MAPPING_TYPE_SKU, $sku);

        if ($productConcreteStorageData === null) {
            return null;
        }

        $productConcreteTransfer = (new ProductConcreteTransfer())->fromArray($productConcreteStorageData, true);
        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())->setName($productConcreteStorageData[static::NAME]);

        return $productConcreteTransfer
            ->setFkProductAbstract($productConcreteStorageData[static::ID_PRODUCT_ABSTRACT])
            ->addLocalizedAttributes($localizedAttributesTransfer);
    }
}
