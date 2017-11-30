<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\StorageProductMapperPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class StorageProductMapperPlugin extends AbstractPlugin implements StorageProductMapperPluginInterface
{
    /**
     * @param array $data
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProduct(array $data, array $selectedAttributes = [])
    {
        return $this->getFactory()
            ->createStorageProductMapper()
            ->mapStorageProduct($data, $selectedAttributes);
    }
}
