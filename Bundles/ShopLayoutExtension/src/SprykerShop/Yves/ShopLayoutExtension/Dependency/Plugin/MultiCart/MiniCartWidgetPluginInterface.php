<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopLayoutExtension\Dependency\Plugin\MultiCart;

interface MiniCartWidgetPluginInterface
{
    const NAME = 'MiniCartWidgetPlugin';

    /**
     * @param int $cartQuantity
     *
     * @return void
     */
    public function initialize($cartQuantity): void;
}
