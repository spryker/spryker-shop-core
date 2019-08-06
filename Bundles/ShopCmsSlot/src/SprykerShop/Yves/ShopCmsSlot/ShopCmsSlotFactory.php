<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShopCmsSlot\Business\CmsSlotExecutor;
use SprykerShop\Yves\ShopCmsSlot\Business\CmsSlotExecutorInterface;
use SprykerShop\Yves\ShopCmsSlot\Twig\TokenParser\ShopCmsSlotTokenParser;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotResolverPluginInterface;

class ShopCmsSlotFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ShopCmsSlot\Twig\TokenParser\ShopCmsSlotTokenParser
     */
    public function createShopCmsSlotTokenParser(): ShopCmsSlotTokenParser
    {
        return new ShopCmsSlotTokenParser();
    }

    /**
     * @return \SprykerShop\Yves\ShopCmsSlot\Business\CmsSlotExecutorInterface
     */
    public function createCmsSlotExecutor(): CmsSlotExecutorInterface
    {
        return new CmsSlotExecutor($this->getShopCmsSlotHandlerPlugin());
    }

    /**
     * @return \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotResolverPluginInterface
     */
    public function getShopCmsSlotHandlerPlugin(): CmsSlotResolverPluginInterface
    {
        return $this->getProvidedDependency(ShopCmsSlotDependencyProvider::PLUGIN_SHOP_CMS_SLOT_HANDLER);
    }
}
