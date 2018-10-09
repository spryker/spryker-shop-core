<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Dependency\Plugin\CurrencyWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface CurrencyWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'CurrencyWidgetPlugin';

    /**
     * @api
     *
     * @return void
     */
    public function initialize(): void;
}
