<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopLayout\Dependency\Plugin\NavigationWidget;

interface NavigationWidgetPluginInterface
{

    const NAME = 'NavigationWidgetPlugin';

    /**
     * @param string $navigationKey
     * @param string $template
     *
     * @return void
     */
    public function initialize($navigationKey, $template): void;
}
