<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductDetailPage\Plugin\StorageRouter;

use Spryker\Shared\ProductStorage\ProductStorageConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductDetailPage\Controller\ProductController;
use SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class ProductDetailPageResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'ProductDetailPage';
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return 'Product';
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return 'detail';
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function mergeResourceData(array $data): array
    {
        return [
            ProductController::ATTRIBUTE_PRODUCT_DATA => $data,
        ];
    }
}
