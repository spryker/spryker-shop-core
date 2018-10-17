<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Dependency\Plugin\BusinessOnBehalfWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\BusinessOnBehalfWidget\Widget\BusinessOnBehalfStatusWidget instead.
 */
interface DisplayOnBehalfBusinessWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'DisplayOnBehalfBusinessWidgetPlugin';

    /**
     * @api
     *
     * @return void
     */
    public function initialize(): void;
}
