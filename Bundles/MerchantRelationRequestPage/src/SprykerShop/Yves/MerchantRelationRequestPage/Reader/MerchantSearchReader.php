<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Reader;

use Generated\Shared\Transfer\MerchantSearchRequestTransfer;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantSearchClientInterface;

class MerchantSearchReader implements MerchantSearchReaderInterface
{
    /**
     * @uses \Spryker\Client\MerchantSearch\Plugin\Elasticsearch\Query\PaginatedMerchantSearchQueryExpanderPlugin::PARAMETER_OFFSET
     *
     * @var string
     */
    protected const PARAMETER_OFFSET = 'offset';

    /**
     * @uses \Spryker\Client\MerchantSearch\Plugin\Elasticsearch\Query\PaginatedMerchantSearchQueryExpanderPlugin::PARAMETER_LIMIT
     *
     * @var string
     */
    protected const PARAMETER_LIMIT = 'limit';

    /**
     * @var int
     */
    protected const MERCHANT_SEARCH_PAGINATION_LIMIT = 100;

    /**
     * @uses \Spryker\Client\MerchantSearch\Plugin\Elasticsearch\ResultFormatter\MerchantSearchResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const MERCHANT_SEARCH_COLLECTION = 'MerchantSearchCollection';

    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantSearchClientInterface
     */
    protected MerchantRelationRequestPageToMerchantSearchClientInterface $merchantSearchClient;

    /**
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantSearchClientInterface $merchantSearchClient
     */
    public function __construct(MerchantRelationRequestPageToMerchantSearchClientInterface $merchantSearchClient)
    {
        $this->merchantSearchClient = $merchantSearchClient;
    }

    /**
     * @return array<string, \Generated\Shared\Transfer\MerchantSearchTransfer>
     */
    public function getMerchantSearchTransferIndexedByMerchantReference(): array
    {
        $indexedMerchantSearchTransfers = [];
        $offset = 0;
        do {
            /** @var \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer */
            $merchantSearchCollectionTransfer = $this->merchantSearchClient
                ->search($this->createMerchantSearchRequest($offset))[static::MERCHANT_SEARCH_COLLECTION];

            foreach ($merchantSearchCollectionTransfer->getMerchants() as $merchantSearchTransfer) {
                $indexedMerchantSearchTransfers[$merchantSearchTransfer->getMerchantReference()] = $merchantSearchTransfer;
            }

            $offset += static::MERCHANT_SEARCH_PAGINATION_LIMIT;
        } while ($merchantSearchCollectionTransfer->getMerchants()->count() !== 0);

        return $indexedMerchantSearchTransfers;
    }

    /**
     * @param int $offset
     *
     * @return \Generated\Shared\Transfer\MerchantSearchRequestTransfer
     */
    protected function createMerchantSearchRequest(int $offset): MerchantSearchRequestTransfer
    {
        return (new MerchantSearchRequestTransfer())->setRequestParameters([
            static::PARAMETER_OFFSET => $offset,
            static::PARAMETER_LIMIT => static::MERCHANT_SEARCH_PAGINATION_LIMIT,
        ]);
    }
}
