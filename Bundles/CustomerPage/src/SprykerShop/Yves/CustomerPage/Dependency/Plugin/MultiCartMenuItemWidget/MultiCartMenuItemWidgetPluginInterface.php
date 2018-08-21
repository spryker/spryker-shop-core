<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Plugin\MultiCartMenuItemWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface MultiCartMenuItemWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'MultiCartMenuItemWidgetPlugin';

    /**
     * @param string $activePage
     *
     * @return void
     */
    public function initialize(string $activePage): void;
}
