<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentWidget\Plugin\Widget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface AgentWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'AgentWidgetPlugin';

    /**
     * @return void
     */
    public function initialize(): void;
}
