<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShopCmsSlot\Business\CmsSlotDataProvider;
use SprykerShop\Yves\ShopCmsSlot\Business\CmsSlotDataProviderInterface;
use SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientInterface;
use SprykerShop\Yves\ShopCmsSlot\Twig\Node\ShopCmsSlotNodeBuilder;
use SprykerShop\Yves\ShopCmsSlot\Twig\Node\ShopCmsSlotNodeBuilderInterface;
use SprykerShop\Yves\ShopCmsSlot\Twig\TokenParser\ShopCmsSlotTokenParser;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface;

class ShopCmsSlotFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ShopCmsSlot\Twig\TokenParser\ShopCmsSlotTokenParser
     */
    public function createShopCmsSlotTokenParser(): ShopCmsSlotTokenParser
    {
        return new ShopCmsSlotTokenParser($this->createShopCmsSlotNodeBuilder());
    }

    /**
     * @return \SprykerShop\Yves\ShopCmsSlot\Twig\Node\ShopCmsSlotNodeBuilderInterface
     */
    public function createShopCmsSlotNodeBuilder(): ShopCmsSlotNodeBuilderInterface
    {
        return new ShopCmsSlotNodeBuilder();
    }

    /**
     * @return \SprykerShop\Yves\ShopCmsSlot\Business\CmsSlotDataProviderInterface
     */
    public function createCmsSlotDataProvider(): CmsSlotDataProviderInterface
    {
        return new CmsSlotDataProvider($this->getCmsSlotContentPlugin(), $this->getCmsSlotClient());
    }

    /**
     * @return \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface
     */
    public function getCmsSlotContentPlugin(): CmsSlotContentPluginInterface
    {
        return $this->getProvidedDependency(ShopCmsSlotDependencyProvider::PLUGIN_CMS_SLOT_CONTENT);
    }

    /**
     * @return \SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientInterface
     */
    public function getCmsSlotClient(): ShopCmsSlotToCmsSlotClientInterface
    {
        return $this->getProvidedDependency(ShopCmsSlotDependencyProvider::CLIENT_CMS_SLOT);
    }
}
