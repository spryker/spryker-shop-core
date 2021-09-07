<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Dependency\Plugin\PriceWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use {@link \SprykerShop\Yves\PriceWidget\Widget\PriceModeSwitcherWidget} instead.
 */
interface PriceModeSwitcherWidgetPluginInterface extends WidgetPluginInterface
{
    /**
     * @var string
     */
    public const NAME = 'PriceModeSwitcherWidgetPlugin';

    /**
     * @api
     *
     * @return void
     */
    public function initialize(): void;
}
