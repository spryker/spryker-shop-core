<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CatalogPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Zed\Category\CategoryConfig;
use SprykerShop\Yves\ShopRouter\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @method \SprykerShop\Yves\CatalogPage\CatalogPageFactory getFactory()
 */
class CatalogPageResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    const ATTRIBUTE_CATEGORY_NODE = 'categoryNode';

    /**
     * @return string
     */
    public function getType()
    {
        return CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE;
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
     * @return array
     */
    public function mergeResourceData(array $data)
    {
        return [
            static::ATTRIBUTE_CATEGORY_NODE => $data,
        ];
    }
}
