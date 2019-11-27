<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Reader;

use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface;
use SprykerShop\Yves\ProductSearchWidget\Expander\ProductConcretePriceExpanderInterface;
use SprykerShop\Yves\ProductSearchWidget\Mapper\ProductConcreteMapperInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    use PermissionAwareTrait;

    /**
     * @uses \Spryker\Client\Catalog\Plugin\Elasticsearch\ResultFormatter\ProductConcreteCatalogSearchResultFormatterPlugin::NAME
     */
    protected const RESULT_FORMATTER = 'ProductConcreteCatalogSearchResultFormatterPlugin';

    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface
     */
    protected $catalogClient;

    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Expander\ProductConcretePriceExpanderInterface $productConcretePriceExpander
     */
    protected $productConcretePriceExpander;

    /**
     * @var \SprykerShop\Yves\ProductSearchWidget\Mapper\ProductConcreteMapperInterface
     */
    protected $productConcreteMapper;

    /**
     * @param \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface $catalogClient
     * @param \SprykerShop\Yves\ProductSearchWidget\Expander\ProductConcretePriceExpanderInterface $productConcretePriceExpander
     * @param \SprykerShop\Yves\ProductSearchWidget\Mapper\ProductConcreteMapperInterface $productConcreteMapper
     */
    public function __construct(
        ProductSearchWidgetToCatalogClientInterface $catalogClient,
        ProductConcretePriceExpanderInterface $productConcretePriceExpander,
        ProductConcreteMapperInterface $productConcreteMapper
    ) {
        $this->catalogClient = $catalogClient;
        $this->productConcretePriceExpander = $productConcretePriceExpander;
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

        if ($productConcreteCriteriaFilterTransfer->getExcludedProductIds()) {
            $productConcretePageSearchTransfers = $this->filterProductConcretePageSearchTransfersByProductIds(
                $productConcretePageSearchTransfers,
                $productConcreteCriteriaFilterTransfer->getExcludedProductIds()
            );
        }

        $productViewTransfers = $this->getMappedProductViewTransfers($productConcretePageSearchTransfers);

        return $this->expandProductViewTransfers($productViewTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[] $productConcretePageSearchTransfers
     * @param int[] $excludedProductIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    protected function filterProductConcretePageSearchTransfersByProductIds($productConcretePageSearchTransfers, array $excludedProductIds): array
    {
        $filteredProductConcretePageSearchTransfers = [];

        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            if (!in_array($productConcretePageSearchTransfer->getFkProduct(), $excludedProductIds, true)) {
                $filteredProductConcretePageSearchTransfers[] = $productConcretePageSearchTransfer;
            }
        }

        return $filteredProductConcretePageSearchTransfers;
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
            $productViewTransfers[] = $this->productConcreteMapper->mapProductConcretePageSearchTransferToProductViewTransfer(
                $productConcretePageSearchTransfer,
                new ProductViewTransfer()
            );
        }

        return $productViewTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function expandProductViewTransfers(array $productViewTransfers): array
    {
        $expandedProductViewTransfers = [];

        foreach ($productViewTransfers as $productViewTransfer) {
            $expandedProductViewTransfers[] = $this->productConcretePriceExpander->expandProductViewTransferWithPrice($productViewTransfer);
        }

        return $expandedProductViewTransfers;
    }
}
