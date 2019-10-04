<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\RedirectPage\Plugin;

use Spryker\Shared\UrlStorage\UrlStorageConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @deprecated Use `\SprykerShop\Yves\RedirectPage\Plugin\StorageRouter\RedirectResourceCreatorPlugin` instead.
 *
 * @method \SprykerShop\Yves\RedirectPage\RedirectPageFactory getFactory()
 */
class RedirectResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    /**
     * @return string
     */
    public function getType()
    {
        return UrlStorageConstants::REDIRECT_RESOURCE_NAME;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return 'RedirectPage';
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return 'Redirect';
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return 'redirect';
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function mergeResourceData(array $data)
    {
        return [
            'meta' => $data,
        ];
    }
}
