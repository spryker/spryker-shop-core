<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Dependency\Client;

interface ProductOptionWidgetToProductOptionStorageClientInterface
{
    /**
     * @param int $idAbstractProduct
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    public function getProductOptions($idAbstractProduct, $localeName);

    /**
     * @param int $idAbstractProduct
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    public function getProductOptionsForCurrentStore($idAbstractProduct);
}
