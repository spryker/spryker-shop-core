<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProfilePage\Plugin;

use Spryker\Shared\MerchantProfileStorage\MerchantProfileStorageConfig;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\ResourceCreatorPluginInterface;

class MerchantProfilePageResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    public const ATTRIBUTE_MERCHANT_PROFILE = 'merchantProfile';

    /**
     * @return string
     */
    public function getType(): string
    {
        return MerchantProfileStorageConfig::MERCHANT_PROFILE_RESOURCE_NAME;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'MerchantProfilePage';
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return 'MerchantProfile';
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return 'index';
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function mergeResourceData(array $data): array
    {
        return [
            static::ATTRIBUTE_MERCHANT_PROFILE => $data,
        ];
    }
}
