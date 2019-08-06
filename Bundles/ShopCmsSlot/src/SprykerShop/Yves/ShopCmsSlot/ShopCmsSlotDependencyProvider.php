<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot;

use RuntimeException;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotResolverPluginInterface;

class ShopCmsSlotDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_SHOP_CMS_SLOT_HANDLER = 'PLUGIN_SHOP_CMS_SLOT_HANDLER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addShopCmsSlotHandlerPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addShopCmsSlotHandlerPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SHOP_CMS_SLOT_HANDLER, function () {
            return $this->getShopCmsSlotHandlerPlugin();
        });

        return $container;
    }

    /**
     * @throws \RuntimeException
     *
     * @return \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotResolverPluginInterface
     */
    protected function getShopCmsSlotHandlerPlugin(): CmsSlotResolverPluginInterface
    {
        throw new RuntimeException('Implement getShopCmsSlotHandlerPlugin().');
    }
}
