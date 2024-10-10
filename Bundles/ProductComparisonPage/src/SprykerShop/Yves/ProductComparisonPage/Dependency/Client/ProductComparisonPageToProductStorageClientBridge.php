<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductComparisonPage\Dependency\Client;

use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class ProductComparisonPageToProductStorageClientBridge implements ProductComparisonPageToProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     */
    public function __construct($productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param array<string, mixed> $data
     * @param string $localeName
     * @param list<string> $selectedAttributes
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageData(
        array $data,
        string $localeName,
        array $selectedAttributes = [],
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer = null
    ): ProductViewTransfer {
        return $this->productStorageClient->mapProductStorageData($data, $localeName, $selectedAttributes, $productStorageCriteriaTransfer);
    }

    /**
     * @param string $mappingType
     * @param array<string> $identifiers
     * @param string $localeName
     *
     * @return array<mixed>
     */
    public function getBulkProductConcreteStorageDataByMapping(
        string $mappingType,
        array $identifiers,
        string $localeName
    ): array {
        return $this->productStorageClient->getBulkProductConcreteStorageDataByMapping($mappingType, $identifiers, $localeName);
    }
}
