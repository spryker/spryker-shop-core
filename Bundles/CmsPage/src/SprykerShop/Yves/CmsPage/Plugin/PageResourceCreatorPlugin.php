<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage\Plugin;

use Spryker\Shared\CmsStorage\CmsStorageConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @deprecated Use `\SprykerShop\Yves\CmsPage\Plugin\StorageRouter\PageResourceCreatorPlugin` instead.
 *
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
            'data' => $data,
        ];
    }
}
