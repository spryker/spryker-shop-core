<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\ProductSalePage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerShop\Client\ProductSalePage\ProductSalePageFactory getFactory()
 */
class ProductSalePageClient extends AbstractClient implements ProductSalePageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $requestParameters
     *
     * @return array
     */
    public function saleSearch(array $requestParameters = [])
    {
        $searchQuery = $this->getFactory()->getSaleSearchQueryPlugin($requestParameters);
        $resultFormatters = $this->getFactory()->getSaleSearchResultFormatterPlugins();

        return $this->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }
}
