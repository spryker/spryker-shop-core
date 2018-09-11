<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Dependency\Plugin\MultiCart;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface MiniCartWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'MiniCartWidgetPlugin';

    /**
     * @api
     *
     * @param int $cartQuantity
     *
     * @return void
     */
    public function initialize($cartQuantity): void;
}
