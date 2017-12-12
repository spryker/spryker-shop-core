<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Dependency\Client;

interface ProductOptionWidgetToProductOptionClientInterface
{
    /**
     * @param int $idAbstractProduct
     * @param int $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductOptionGroupCollectionTransfer
     */
    public function getProductOptions($idAbstractProduct, $localeName);
}
