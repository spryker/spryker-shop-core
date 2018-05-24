<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\BarcodeWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface BarcodeWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'BarcodeWidgetPlugin';

    /**
     * @param string $productSku
     *
     * @return void
     */
    public function initialize(string $productSku): void;
}
