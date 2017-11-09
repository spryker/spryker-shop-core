<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopRouter\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @method \SprykerShop\Yves\CmsPage\CmsPageFactory getFactory()
 */
class PageResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'page';
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return 'CmsPage';
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return 'Cms';
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return 'page';
    }

    /**
     * @return array
     */
    public function mergeResourceData(array $data)
    {
        return [
            'meta' => $data
        ];
    }
}
