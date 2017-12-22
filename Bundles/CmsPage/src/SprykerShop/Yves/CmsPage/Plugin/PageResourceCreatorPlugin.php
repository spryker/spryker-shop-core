<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsPage\Plugin;

use Spryker\Shared\CmsStorage\CmsStorageConstants;
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
        return CmsStorageConstants::CMS_PAGE_RESOURCE_NAME;
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
     * @param array $data
     *
     * @return array
     */
    public function mergeResourceData(array $data)
    {
        return [
            'data' => $data
        ];
    }
}
