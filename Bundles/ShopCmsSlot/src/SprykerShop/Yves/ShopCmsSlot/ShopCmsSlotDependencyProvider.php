<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot;

use RuntimeException;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientBridge;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotPluginInterface;

class ShopCmsSlotDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CMS_SLOT = 'CLIENT_CMS_SLOT';
    public const PLUGIN_SHOP_CMS_SLOT_HANDLER = 'PLUGIN_SHOP_CMS_SLOT_HANDLER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCmsSlotPlugin($container);
        $container = $this->addCmsSlotClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsSlotPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SHOP_CMS_SLOT_HANDLER, function () {
            return $this->getCmsSlotPlugin();
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
     * @throws \RuntimeException
     *
     * @return \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotPluginInterface
     */
    protected function getCmsSlotPlugin(): CmsSlotPluginInterface
    {
        throw new RuntimeException('Implement getCmsSlotPlugin().');
    }
}
