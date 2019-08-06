<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotRequestTransfer;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotResolverPluginInterface;

class CmsSlotExecutor implements CmsSlotExecutorInterface
{
    /**
     * @var \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotResolverPluginInterface
     */
    protected $shopCmsSlotHandlerPlugin;

    /**
     * @param \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotResolverPluginInterface $shopCmsSlotHandlerPlugin
     */
    public function __construct(CmsSlotResolverPluginInterface $shopCmsSlotHandlerPlugin)
    {
        $this->shopCmsSlotHandlerPlugin = $shopCmsSlotHandlerPlugin;
    }

    /**
     * @param string $cmsSlotKey
     * @param array $provided
     * @param string[] $required
     * @param string[] $autofulfil
     *
     * @return string
     */
    public function getSlotContent(string $cmsSlotKey, array $provided, array $required, array $autofulfil): string
    {
        $cmsSlotRequestTransfer = (new CmsSlotRequestTransfer())
            ->setKey($cmsSlotKey)
            ->setProvided($provided)
            ->setRequired($required)
            ->setAutofulfil($autofulfil);

        return $this->shopCmsSlotHandlerPlugin->getSlotContent($cmsSlotRequestTransfer);
    }
}
