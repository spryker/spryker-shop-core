<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\RedirectPage\Plugin\StorageRouter;

use Spryker\Shared\UrlStorage\UrlStorageConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @method \SprykerShop\Yves\RedirectPage\RedirectPageFactory getFactory()
 */
class RedirectResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return UrlStorageConstants::REDIRECT_RESOURCE_NAME;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'RedirectPage';
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return 'Redirect';
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return 'redirect';
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function mergeResourceData(array $data): array
    {
        return [
            'meta' => $data,
        ];
    }
}
