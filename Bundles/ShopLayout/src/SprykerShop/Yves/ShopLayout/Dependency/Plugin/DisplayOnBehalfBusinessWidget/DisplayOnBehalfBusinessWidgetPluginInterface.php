<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopLayout\Dependency\Plugin\DisplayOnBehalfBusinessWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface DisplayOnBehalfBusinessWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'DisplayOnBehalfBusinessWidget';

    /**
     * @return void
     */
    public function initialize(): void;
}
