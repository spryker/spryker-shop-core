<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Plugin\Twig;

use SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin;

/**
 * @method \SprykerShop\Yves\ShopCmsSlot\ShopCmsSlotFactory getFactory()
 */
class ShopCmsSlotTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @return \Twig\TokenParser\TokenParserInterface[]
     */
    public function getTokenParsers(): array
    {
        return [
            $this->getFactory()->createShopCmsSlotTokenParser(),
        ];
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
        return $this->getFactory()->createCmsSlotExecutor()->getSlotContent($cmsSlotKey, $provided, $required, $autofulfil);
    }
}
