<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Dependency\Plugin\NavigationWidget;

use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

interface NavigationWidgetPluginInterface extends WidgetPluginInterface
{
    const NAME = 'NavigationWidgetPlugin';

    /**
     * @api
     *
     * @param string $navigationKey
     * @param string $template
     *
     * @return void
     */
    public function initialize($navigationKey, $template): void;
}
