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
     * @return void
     */
    public function initialize(): void;
}
