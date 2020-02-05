<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;

interface ProductDetailPageToProductStorageClientInterface
{
    /**
     * @param array $data
     * @param string $localeName
     * @param array $selectedAttributes
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer|null $productOfferStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageData(array $data, $localeName, array $selectedAttributes = [], ?ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer = null);

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool;

    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteRestricted(int $idProductConcrete): bool;
}
