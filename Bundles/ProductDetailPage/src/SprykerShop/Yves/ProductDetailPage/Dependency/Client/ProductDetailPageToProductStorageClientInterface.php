<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Dependency\Client;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteStorageTransfer;

interface ProductDetailPageToProductStorageClientInterface
{
    /**
     * @param array $data
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function mapProductStorageDataForCurrentLocale(array $data, array $selectedAttributes = []);
}
