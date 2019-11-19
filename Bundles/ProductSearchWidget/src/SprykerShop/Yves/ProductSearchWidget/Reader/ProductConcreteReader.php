<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Reader;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToPriceProductStorageClientInterface;
use SprykerShop\Yves\ProductSearchWidget\Mapper\ProductConcreteMapperInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @uses \Spryker\Client\Catalog\Plugin\Elasticsearch\ResultFormatter\ProductConcreteCatalogSearchResultFormatterPlugin::NAME
     */
    protected const RESULT_FORMATTER = 'ProductConcreteCatalogSearchResultFormatterPlugin';

    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface
     */
    protected $catalogClient;

    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Mapper\ProductConcreteMapperInterface
     */
    protected $productConcreteMapper;

    /**
     * @param \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface $catalogClient
     * @param \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerShop\Yves\ProductSearchWidget\Mapper\ProductConcreteMapperInterface $productConcreteMapper
     */
    public function __construct(
        ProductSearchWidgetToCatalogClientInterface $catalogClient,
        ProductSearchWidgetToPriceProductStorageClientInterface $priceProductStorageClient,
        ProductConcreteMapperInterface $productConcreteMapper
    ) {
        $this->catalogClient = $catalogClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->productConcreteMapper = $productConcreteMapper;
    }

    /***
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function searchProductConcretesByFullText(ProductConcreteCriteriaFilterTransfer $productConcreteCriteriaFilterTransfer): array
    {
        $searchResults = $this->catalogClient->searchProductConcretesByFullText($productConcreteCriteriaFilterTransfer);

        $productConcretePageSearchTransfers = $searchResults[static::RESULT_FORMATTER] ?? [];

        if (!$productConcretePageSearchTransfers) {
            return [];
        }

        return $this->getMappedProductViewTransfers($productConcretePageSearchTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $productConcretePageSearchTransfers
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function getMappedProductViewTransfers(array $productConcretePageSearchTransfers): array
    {
        $productViewTransfers = [];

        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $productViewTransfer = $this->productConcreteMapper->mapProductConcretePageSearchTransferToProductViewTransfer(
                $productConcretePageSearchTransfer,
                new ProductViewTransfer()
            );

            $productViewTransfers[] = $this->expandProductViewTransferWithPrice($productViewTransfer);
        }

        return $productViewTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function expandProductViewTransferWithPrice(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($productViewTransfer->getQuantity() ?: 1)
            ->setIdProduct($productViewTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productViewTransfer->getIdProductAbstract());

        $currentProductPriceTransfer = $this->priceProductStorageClient->getResolvedCurrentProductPriceTransfer($priceProductFilterTransfer);

        return $productViewTransfer->setPrice($currentProductPriceTransfer->getSumPrice());
    }
}
