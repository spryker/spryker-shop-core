<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientBridge;
use SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotStorageClientBridge;

class ShopCmsSlotDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CMS_SLOT = 'CLIENT_CMS_SLOT';
    public const CLIENT_CMS_SLOT_STORAGE = 'CLIENT_CMS_SLOT_STORAGE';
    public const PLUGINS_CMS_SLOT_CONTENT = 'PLUGINS_CMS_SLOT_CONTENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCmsSlotContentPlugin($container);
        $container = $this->addCmsSlotClient($container);
        $container = $this->addCmsSlotStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsSlotContentPlugin(Container $container): Container
    {
        $container->set(static::PLUGINS_CMS_SLOT_CONTENT, function () {
            return $this->getCmsSlotContentPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsSlotClient(Container $container): Container
    {
        $container->set(static::CLIENT_CMS_SLOT, function (Container $container) {
            return new ShopCmsSlotToCmsSlotClientBridge($container->getLocator()->cmsSlot()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsSlotStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CMS_SLOT_STORAGE, function (Container $container) {
            return new ShopCmsSlotToCmsSlotStorageClientBridge($container->getLocator()->cmsSlotStorage()->client());
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface[]
     */
    protected function getCmsSlotContentPlugins(): array
    {
        return [];
    }
}
