<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Client;

interface ProductDetailPageToProductClientInterface
{
    /**
     * @param array $data
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProductForCurrentLocale(array $data, array $selectedAttributes = []);
}
