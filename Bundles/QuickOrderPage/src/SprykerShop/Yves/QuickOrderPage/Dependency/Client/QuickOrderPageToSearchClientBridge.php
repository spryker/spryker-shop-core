<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Client;

use Spryker\Client\Search\SearchClientInterface;

class QuickOrderPageToSearchClientBridge implements QuickOrderPageToSearchClientInterface
{
    /**
     * @var \Spryker\Client\Search\SearchClientInterface
     */
    protected $searchClient;

    /**
     * @param \Spryker\Client\Search\SearchClientInterface $searchClient
     */
    public function __construct(SearchClientInterface $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Elastica\ResultSet|array
     */
    public function searchQueryString($searchString, $limit = null, $offset = null)
    {
        return $this->searchClient->searchQueryString($searchString, $limit, $offset);
    }
}
