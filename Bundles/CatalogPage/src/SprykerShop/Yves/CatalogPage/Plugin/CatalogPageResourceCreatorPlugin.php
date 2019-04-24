<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Plugin;

use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @deprecated Use `\SprykerShop\Yves\CatalogPage\Plugin\StorageRouter\CatalogPageResourceCreatorPlugin` instead.
 *
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageFactory getFactory()
 */
class CatalogPageResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    public const ATTRIBUTE_CATEGORY_NODE = 'categoryNode';

    /**
     * @return string
     */
    public function getType()
    {
        return CategoryStorageConstants::CATEGORY_NODE_RESOURCE_NAME;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return 'CatalogPage';
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return 'Catalog';
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return 'index';
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function mergeResourceData(array $data)
    {
        return [
            static::ATTRIBUTE_CATEGORY_NODE => $data,
        ];
    }
}
