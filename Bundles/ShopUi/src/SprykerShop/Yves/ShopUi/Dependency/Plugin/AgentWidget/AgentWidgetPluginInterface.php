<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Dependency\Plugin\AgentWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\AgentWidget\Widget\AgentControlBarWidget instead.
 */
interface AgentWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'AgentWidgetPlugin';

    /**
     * @api
     *
     * @return void
     */
    public function initialize(): void;
}
