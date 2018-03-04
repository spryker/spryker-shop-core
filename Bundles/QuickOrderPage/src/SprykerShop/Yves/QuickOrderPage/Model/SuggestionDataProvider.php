<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Model;

use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig;

class SuggestionDataProvider implements SuggestionDataProviderInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $config;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Model\ProductFinderInterface
     */
    protected $productFinder;

    /**
     * SuggestionDataProvider constructor.
     *
     * @param \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig $config
     * @param \SprykerShop\Yves\QuickOrderPage\Model\ProductFinderInterface $productFinder
     */
    public function __construct(QuickOrderPageConfig $config, ProductFinderInterface $productFinder)
    {
        $this->config = $config;
        $this->productFinder = $productFinder;
    }

    public function getSuggestionCollection(string $searchString, string $searchField): array
    {
        $limit = $this->config->getSuggestionResultsLimit();

        $productViewTransfers = $this->productFinder
            ->getSearchResults($searchString, $searchField, $limit);

        return $this->formatSuggestions($productViewTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return array
     */
    protected function formatSuggestions(array $productViewTransfers): array
    {
        return array_map(function (ProductViewTransfer $productViewTransfer) {
            return [
                'value' => $productViewTransfer->getSku() . ' - ' . $productViewTransfer->getName(),
                'data' => [
                    'idAbstractProduct' => $productViewTransfer->getIdProductAbstract(),
                    'sku' => $productViewTransfer->getSku(),
                    'price' => $productViewTransfer->getPrice(),
                    'available' => $productViewTransfer->getAvailable(),
                ],
            ];
        }, $productViewTransfers);
    }
}
