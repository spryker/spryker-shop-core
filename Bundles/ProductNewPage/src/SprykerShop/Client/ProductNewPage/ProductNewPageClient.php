<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\ProductNewPage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerShop\Client\ProductNewPage\ProductNewPageFactory getFactory()
 */
class ProductNewPageClient extends AbstractClient implements ProductNewPageClientInterface
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
    public function findNewProducts(array $requestParameters = [])
    {
        $searchQuery = $this->getFactory()->getNewProductsQueryPlugin($requestParameters);
        $resultFormatters = $this->getFactory()->getNewProductsSearchResultFormatterPlugins();

        return $this->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }

}
