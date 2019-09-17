<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Twig\Node;

use Twig\Node\Node;

interface ShopCmsSlotNodeBuilderInterface
{
    /**
     * @param string $cmsSlotKey
     * @param array $nodes
     * @param array $attributes
     * @param int $lineno
     * @param string $tag
     *
     * @return \Twig\Node\Node
     */
    public function createShopCmsSlotNode(
        string $cmsSlotKey,
        array $nodes,
        array $attributes,
        int $lineno,
        string $tag
    ): Node;
}
