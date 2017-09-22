<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\Plugin\Pimple;
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
            ->mapStorageProduct($data, $this->getRequest(), $selectedAttributes);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     * TODO: do we really need this?
     */
    protected function getRequest()
    {
        return (new Pimple())->getApplication()['request_stack']->getCurrentRequest();
    }

}
