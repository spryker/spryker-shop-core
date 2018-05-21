<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Dependency\Plugin\BusinessOnBehalfWidget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface MenuItemBusinessOnBehalfWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'MenuItemBusinessOnBehalfWidgetPlugin';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    public function initialize(): void;
}
