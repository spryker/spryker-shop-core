<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Plugin;

use Spryker\Shared\ProductStorage\ProductStorageConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductDetailPage\Controller\ProductController;
use SprykerShop\Yves\ShopRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @deprecated Use `\SprykerShop\Yves\ProductDetailPage\Plugin\StorageRouter\ProductDetailPageResourceCreatorPlugin` instead.
 *
 * @method \SprykerShop\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class ProductDetailPageResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    /**
     * @return string
     */
    public function getType()
    {
        return ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return 'ProductDetailPage';
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return 'Product';
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return 'detail';
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function mergeResourceData(array $data)
    {
        return [
            ProductController::ATTRIBUTE_PRODUCT_DATA => $data,
        ];
    }
}
