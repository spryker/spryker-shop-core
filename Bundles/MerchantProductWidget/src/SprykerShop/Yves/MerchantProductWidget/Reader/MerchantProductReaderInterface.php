<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Reader;

use Generated\Shared\Transfer\MerchantProductViewTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface MerchantProductReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantProductViewTransfer|null
     */
    public function findMerchantProductView(ProductViewTransfer $productViewTransfer, string $localeName): ?MerchantProductViewTransfer;

    /**
     * @param string $sku
     * @param string $locale
     *
     * @return string|null
     */
    public function findMerchantReferenceByConcreteSku(string $sku, string $locale): ?string;
}
